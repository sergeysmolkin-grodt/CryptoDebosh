<?php

namespace CryptoDebosh\Application\Services;

use Binance\Exception\MissingArgumentException;
use Binance\Spot;
use GuzzleHttp\Client;

class TradingBotService
{
    private $apiKey;
    private $secretKey;
    private $client;

    public function __construct($apiKey, $secretKey)
    {
        $this->apiKey = trim($apiKey);  // Ensure keys are trimmed of any whitespace
        $this->secretKey = trim($secretKey);

        $guzzleClient = new Client([
            'verify' => false, // Disable SSL verification for testing
            'debug' => true,   // Enable debugging information
        ]);

        $this->client = new Spot([
            'key' => $this->apiKey,
            'secret' => $this->secretKey,
            'http_client_handler' => $guzzleClient,
            'recvWindow' => 60000,
            'options' => [
                'adjustForTimeDifference' => true
            ]
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
        echo "Crypto Balance: $cryptoBalance\n";

        $shortMA = $this->getMovingAverage($symbol, '1m', 5);
        $longMA = $this->getMovingAverage($symbol, '1m', 20);

        echo "Short Moving Average: $shortMA\n";
        echo "Long Moving Average: $longMA\n";

        if ($shortMA > $longMA && $usdtBalance >= $investment) {
            // Buy
            $quantity = bcdiv($investment, $shortMA, 6); // Ensure quantity is a string with 6 decimal places
            echo "Attempting to purchase $quantity BTC with $investment USDT\n";
            $response = $this->client->newOrder('BTCUSDT', 'BUY', 'MARKET', [
                'quantity' => $quantity,
                'recvWindow' => 60000, // Set recvWindow to 60 seconds
            ]);
            echo "Purchased BTC worth $investment USDT. Response: " . json_encode($response) . "\n";
        } elseif ($shortMA < $longMA) {
            // Sell
            if ($cryptoBalance !== null && $cryptoBalance > 0) {
                $quantity = bcdiv($cryptoBalance, '1', 6); // Ensure quantity is a string with 6 decimal places
                echo "Attempting to sell $quantity BTC\n";
                $response = $this->client->newOrder('BTCUSDT', 'SELL', 'MARKET', [
                    'quantity' => $quantity,
                    'recvWindow' => 60000, // Set recvWindow to 60 seconds
                ]);
                echo "Sold all available BTC. Response: " . json_encode($response) . "\n";
            } else {
                throw new \Exception("Crypto balance not found or zero.");
            }
        } else {
            echo "No conditions for buying or selling.\n";
        }
    }
}
