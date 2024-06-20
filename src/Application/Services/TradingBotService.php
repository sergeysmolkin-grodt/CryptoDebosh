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
        $this->key = trim($key);  // Ensure keys are trimmed of any whitespace
        $this->secret = trim($secret);

        $guzzleClient = new Client([
            'verify' => false, // Disable SSL verification for testing
            'debug' => true,   // Enable debugging information
        ]);

        $this->client = new Spot([
            'key' => $this->key,
            'secret' => $this->secret,
            'recvWindow' => 60000,
            'http_client_handler' => $guzzleClient,
        ]);
    }

    public function getMovingAverage($symbol, $interval, $limit)
    {
        $klines = $this->client->klines($symbol, $interval, ['limit' => $limit]);
        $sum = 0;
        foreach ($klines as $kline) {
            $sum += ($kline[1] + $kline[4]) / 2; // average price (open + close) / 2
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

        // Force buy for testing
        if ($usdtBalance >= $investment) {
            // Buy
            $quantity = bcdiv($investment, $shortMA, 6); // Ensure quantity is a string with 6 decimal places
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
            // Sell
            if ($cryptoBalance !== null && $cryptoBalance > 0) {
                $quantity = bcdiv($cryptoBalance, '1', 6); // Ensure quantity is a string with 6 decimal places
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
}
