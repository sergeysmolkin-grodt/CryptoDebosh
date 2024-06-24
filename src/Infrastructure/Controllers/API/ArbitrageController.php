<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\API;

use App\Infrastructure\Services\External\ArbitrageService;
use App\Infrastructure\Services\External\CoinbaseApiService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Infrastructure\Services\External\BinanceApiService;

class ArbitrageController extends AbstractController
{
    private $binanceApiService;
    private $coinbaseApiService;
    private $logger;
    private $arbitrageService;

    public function __construct(BinanceApiService $binanceApiService, CoinbaseApiService $coinbaseApiService, LoggerInterface $logger, ArbitrageService $arbitrageService)
    {
        $this->binanceApiService = $binanceApiService;
        $this->coinbaseApiService = $coinbaseApiService;
        $this->logger = $logger;
        $this->arbitrageService = $arbitrageService;
    }

    public function index(): Response
    {
        $opportunities = $this->arbitrageService->findArbitrageOpportunities();

        foreach ($opportunities as $opportunity) {
            $this->executeArbitrage($opportunity);
        }

        return $this->json($opportunities);
    }



    private function executeArbitrage($opportunity): void
    {
        $symbol = $opportunity['symbol'];
        $buyPrice = $opportunity['buy_price'];
        $sellPrice = $opportunity['sell_price'];
        $quantity = 10;

        try {
            $buyOrderResponse = $this->binanceApiService->placeOrder($symbol, 'BUY', 'LIMIT', $quantity, $buyPrice);
            if (isset($buyOrderResponse['orderId'])) {
                echo "Buy order executed successfully. Order ID: " . $buyOrderResponse['orderId'] . "\n";
            } else {
                echo "Buy order execution failed.\n";
            }

            $sellOrderResponse = $this->binanceApiService->placeOrder($symbol, 'SELL', 'LIMIT', $quantity, $sellPrice);
            if (isset($sellOrderResponse['orderId'])) {
                echo "Sell order executed successfully. Order ID: " . $sellOrderResponse['orderId'] . "\n";
            } else {
                echo "Sell order execution failed.\n";
            }

            $this->logTransaction($symbol, $buyPrice, $sellPrice, $quantity);
        } catch (\Exception $e) {
            $this->logError($e->getMessage());
        }
    }

    private function logTransaction($symbol, $buyPrice, $sellPrice, $quantity)
    {
        file_put_contents('transactions.log', date('Y-m-d H:i:s') . " - Successful arbitrage: $symbol - Buy: $buyPrice, Sell: $sellPrice, Quantity: $quantity\n", FILE_APPEND);
    }

    private function logError($errorMessage)
    {
        file_put_contents('errors.log', date('Y-m-d H:i:s') . " - Error: $errorMessage\n", FILE_APPEND);
    }

}