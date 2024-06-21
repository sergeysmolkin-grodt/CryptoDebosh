<?php

namespace CryptoDebosh\Application\Services;

use Binance\Spot;
use GuzzleHttp\Client;

class MovingAverageStrategy
{
    private string $key;
    private string $secret;
    private Spot $client;

    public function __construct($key, $secret)
    {
        $this->key = trim($key);
        $this->secret = trim($secret);

        $guzzleClient = new Client([
            'verify' => false,
            'debug' => true,
        ]);

        $this->client = new Spot([
            'key' => $this->key,
            'secret' => $this->secret,
            'http_client_handler' => $guzzleClient,
            'recvWindow' => 60000,
        ]);
    }

    public function getMovingAverage($symbol, $interval, $limit): float|int
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

    public function trade($symbol, $investment)
    {
        try {
            $accountInfo = $this->client->account(['recvWindow' => 60000]);
        } catch (\Exception $e) {
            echo "Error fetching account info: " . $e->getMessage() . "\n";
            return;
        }

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

        $shortMA = $this->getMovingAverage($symbol, '15m', 5);
        $longMA = $this->getMovingAverage($symbol, '15m', 10);
        $rsi = $this->calculateRSI($symbol, '15m');

        echo "Short Moving Average: $shortMA\n";
        echo "Long Moving Average: $longMA\n";
        echo "RSI: $rsi\n";

        if ($shortMA > $longMA && $usdtBalance >= $investment && $rsi < 30) {
            $quantity = bcdiv($investment, $shortMA, 8);
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

    public function run($symbol, $investment, $intervalSeconds = 60): void
    {
        while (true) {
            try {
                $this->trade($symbol, $investment);
                sleep($intervalSeconds);
            } catch (\Exception $e) {
                echo "Error in trading loop: " . $e->getMessage() . "\n";
                sleep($intervalSeconds);
            }
        }
    }
}
