<?php

namespace CryptoDebosh\Application\Services;

use Binance\Exception\MissingArgumentException;
use Binance\Spot;
use GuzzleHttp\Client;

class TradingBotService
{
    private string $key;
    private string $secret;
    private Spot $client;

    public function __construct($key, $secret)
    {
        $this->key = trim($key);
        $this->secret = trim($secret);
        dd($this->secret, $this->key);
        $guzzleClient = new Client([
            'verify' => false,
            'debug' => true,
        ]);

        $this->client = new Spot([
            'key' => $this->key,
            'secret' => $this->secret,
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

    /**
     * @throws MissingArgumentException
     */
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

        $shortMA = $this->getMovingAverage($symbol, '1m', 5);
        $longMA = $this->getMovingAverage($symbol, '1m', 20);

        echo "Short Moving Average: $shortMA\n";
        echo "Long Moving Average: $longMA\n";

        if ($usdtBalance >= $investment) {
            $quantity = bcdiv($investment, $shortMA, 6);
            echo "Attempting to purchase $quantity BTC with $investment USDT\n";
            try {
                $response = $this->client->newOrder(
                    'BTCUSDT',
                    'BUY',
                    'MARKET',
                    [
                        'quantity' => $quantity
                    ]
                );
                echo "Purchased BTC worth $investment USDT. Response: " . json_encode($response) . "\n";
            } catch (\Exception $e) {
                echo "Error executing buy order: " . $e->getMessage() . "\n";
            }
        } else {
            echo "No conditions for buying. Checking for selling conditions...\n";
            if ($cryptoBalance !== null && $cryptoBalance > 0) {
                $quantity = bcdiv($cryptoBalance, '1', 6);
                echo "Attempting to sell $quantity BTC\n";
                try {
                    $response = $this->client->newOrder(
                        'BTCUSDT',
                        'SELL',
                        'MARKET',
                        [
                            'quantity' => $quantity
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
            } catch (\Exception $e) {
                echo "Error in trading loop: " . $e->getMessage() . "\n";
            }
            sleep($intervalSeconds);
        }
    }
}
