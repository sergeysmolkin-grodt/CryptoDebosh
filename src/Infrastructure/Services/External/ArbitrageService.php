<?php

declare(strict_types=1);

namespace App\Infrastructure\Services\External;

use App\Infrastructure\Services\External\BinanceApiService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ArbitrageService
{
    private $binanceApiService;
    private $coinbaseApiService;
    private $logger;
    private $tradingFee;

    public function __construct(BinanceApiService $binanceApiService, CoinbaseApiService $coinbaseApiService, LoggerInterface $logger, float $tradingFee)
    {
        $this->binanceApiService = $binanceApiService;
        $this->coinbaseApiService = $coinbaseApiService;
        $this->logger = $logger;
        $this->tradingFee = $tradingFee;
    }

    public function executeArbitrage($opportunity): void
    {
        $symbol = $opportunity['symbol'];
        $buyPrice = $opportunity['buy_price'];
        $sellPrice = $opportunity['sell_price'];
        $quantity = 0.001;

        $potentialProfit = ($sellPrice - $buyPrice) * $quantity - 2 * $this->tradingFee;

        if ($potentialProfit > 0) {
            try {
                $this->binanceApiService->placeOrder($symbol, 'BUY', 'LIMIT', $quantity, $buyPrice);
                $this->binanceApiService->placeOrder($symbol, 'SELL', 'LIMIT', $quantity, $sellPrice);
                $this->logTransaction($symbol, $buyPrice, $sellPrice, $quantity, $potentialProfit);
            } catch (\Exception $e) {
                $this->logError($e->getMessage());
            }
        }
    }

    public function findArbitrageOpportunities(OutputInterface $output): array
    {
        $prices = $this->binanceApiService->getPrices();

        $opportunities = [];
        $externalPrices = $this->coinbaseApiService->getPrices();

        foreach ($prices as $price) {
            foreach ($externalPrices as $externalPrice) {
                if ($price['symbol'] === $externalPrice['symbol'] && $price['price'] < $externalPrice['price']) {
                    $opportunity = [
                        'symbol' => $price['symbol'],
                        'buy_price' => $price['price'],
                        'sell_price' => $externalPrice['price'],
                    ];
                    $opportunities[] = $opportunity;
                    $output->writeln('Found opportunity: ' . json_encode($opportunity));
                }
            }
        }

        if (!empty($opportunities)) {
            foreach ($opportunities as $opportunity) {
                $this->executeArbitrage($opportunity);
                $output->writeln('Executed arbitrage for opportunity: ' . json_encode($opportunity));
            }
        } else {
            $output->writeln('No arbitrage opportunities found.');
        }

        return $opportunities;
    }
    private function logTransaction($symbol, $buyPrice, $sellPrice, $quantity, $profit)
    {
        $this->logger->info("Successful arbitrage: $symbol - Buy: $buyPrice, Sell: $sellPrice, Quantity: $quantity, Profit: $profit");
    }

    private function logError($errorMessage)
    {
        $this->logger->error("Error: $errorMessage");
    }
}