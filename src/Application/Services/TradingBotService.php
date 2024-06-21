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

    private function getMinOrderSize($symbol)
    {
        $exchangeInfo = $this->client->exchangeInfo();
        foreach ($exchangeInfo['symbols'] as $s) {
            if ($s['symbol'] === $symbol) {
                foreach ($s['filters'] as $filter) {
                    if ($filter['filterType'] === 'LOT_SIZE') {
                        return $filter['minQty'];
                    }
                }
            }
        }
        throw new \Exception("LOT_SIZE filter not found for symbol $symbol");
    }

    private function roundToLotSize($quantity, $minQty)
    {
        return bcmul(bcdiv($quantity, $minQty, 0), $minQty, 8);
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
     * @throws \Exception
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

        $minOrderSize = $this->getMinOrderSize($symbol);
        $quantity = bcdiv($investment, $shortMA, 8);
        $quantity = $this->roundToLotSize($quantity, $minOrderSize);

        if ($usdtBalance >= $investment) {
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
            if ($cryptoBalance !== null && $cryptoBalance > 0) {
                $quantity = $this->roundToLotSize($cryptoBalance, $minOrderSize);
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
            } catch (\Exception $e) {
                echo "Error in trading loop: " . $e->getMessage() . "\n";
            }
            sleep($intervalSeconds);
        }
    }
}
