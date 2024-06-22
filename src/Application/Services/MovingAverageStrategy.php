<?php



namespace App\Application\Services;

use Binance\Spot;
use App\Application\Contracts\TradingStrategyInterface;

class MovingAverageStrategy implements TradingStrategyInterface
{
    private Spot $client;

    public function __construct(Spot $client)
    {
        $this->client = $client;
    }

    public function execute(string $symbol, float $investment): void
    {
        $accountInfo = $this->client->account(['recvWindow' => 60000]);

        $balances = $accountInfo['balances'];
        $usdtBalance = null;
        $cryptoBalance = null;

        foreach ($balances as $b) {
            if ($b['asset'] === 'USDT') {
                $usdtBalance = $b['free'];
            }
            if ($b['asset'] === explode('USDT', $symbol)[0]) {
                $cryptoBalance = $b['free'];
            }
        }

        if ($usdtBalance === null) {
            throw new \Exception("USDT balance not found.");
        }

        echo "USDT Balance: $usdtBalance\n";
        echo "Crypto Balance (BTC): $cryptoBalance\n";

        $shortMA = $this->getMovingAverage($symbol, '30m', 15);
        $longMA = $this->getMovingAverage($symbol, '30m', 60);
        $rsi = $this->calculateRSI($symbol, '30m');

        echo "Short Moving Average: $shortMA\n";
        echo "Long Moving Average: $longMA\n";
        echo "RSI: $rsi\n";

        $filters = $this->getSymbolFilters($symbol);
        $minQty = null;
        $stepSize = null;

        foreach ($filters as $filter) {
            if ($filter['filterType'] === 'LOT_SIZE') {
                $minQty = $filter['minQty'];
                $stepSize = $filter['stepSize'];
                echo "Min LOT_SIZE: $minQty, Step Size: $stepSize\n";
            }
        }

        $quantity = bcdiv($investment, $shortMA, 8);

        if ($quantity < $minQty) {
            $quantity = $minQty;
        }

        $precision = log10(1 / $stepSize);
        $quantity = floor($quantity / $stepSize) * $stepSize;
        $quantity = number_format($quantity, $precision, '.', '');

        if ($shortMA > $longMA && $usdtBalance >= $investment && $rsi < 30) {
            echo "Attempting to purchase $quantity BTC with $investment USDT\n";
            try {
                $response = $this->client->newOrder(
                    'BTCUSDT',
                    'BUY',
                    'MARKET',
                    [
                        'quantity' => $quantity,
                    ]
                );
                echo "Purchased BTC worth $investment USDT. Response: " . json_encode($response) . "\n";
            } catch (\Exception $e) {
                echo "Error executing buy order: " . $e->getMessage() . "\n";
            }
        } else {
            echo "No conditions for buying. Checking for selling conditions...\n";
            if ($cryptoBalance !== null && $cryptoBalance > 0 && $rsi > 70) {
                $quantity = bcdiv($cryptoBalance, '1', 8);
                $quantity = floor($quantity / $stepSize) * $stepSize;
                $quantity = number_format($quantity, $precision, '.', '');
                echo "Attempting to sell $quantity BTC\n";
                try {
                    $response = $this->client->newOrder(
                        'BTCUSDT',
                        'SELL',
                        'MARKET',
                        [
                            'quantity' => $quantity,
                        ]
                    );
                    echo "Sold all available BTC. Response: " . json_encode($response) . "\n";
                } catch (\Exception $e) {
                    echo "Error executing sell order: " . $e->getMessage() . "\n";
                }
            } else {
                echo "Crypto balance not found or zero.\n";
            }
        }
    }

    private function getMovingAverage($symbol, $interval, $limit): float|int
    {
        $klines = $this->client->klines($symbol, $interval, ['limit' => $limit]);
        $sum = 0;
        foreach ($klines as $kline) {
            $sum += ($kline[1] + $kline[4]) / 2;
        }
        return $sum / $limit;
    }

    private function calculateRSI($symbol, $interval, $limit = 14): float
    {
        $klines = $this->client->klines($symbol, $interval, ['limit' => $limit + 1]);
        $gains = 0;
        $losses = 0;

        for ($i = 1, $iMax = count($klines); $i < $iMax; $i++) {
            $change = $klines[$i][4] - $klines[$i - 1][4];
            if ($change > 0) {
                $gains += $change;
            } else {
                $losses += abs($change);
            }
        }

        $averageGain = $gains / $limit;
        $averageLoss = $losses / $limit;

        if ($averageLoss == 0) {
            return 100;
        }

        $rs = $averageGain / $averageLoss;
        $rsi = 100 - (100 / (1 + $rs));

        return $rsi;
    }

    private function getSymbolFilters($symbol)
    {
        $exchangeInfo = $this->client->exchangeInfo();
        foreach ($exchangeInfo['symbols'] as $s) {
            if ($s['symbol'] === $symbol) {
                return $s['filters'];
            }
        }
        throw new \Exception("Filters not found for symbol $symbol");
    }
}