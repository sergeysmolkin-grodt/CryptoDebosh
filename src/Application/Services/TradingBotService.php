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

    // Method to retrieve symbol filters such as LOT_SIZE and MIN_NOTIONAL
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

    // Method to calculate the moving average
    public function getMovingAverage($symbol, $interval, $limit): float|int
    {
        $klines = $this->client->klines($symbol, $interval, ['limit' => $limit]);
        $sum = 0;
        foreach ($klines as $kline) {
            $sum += ($kline[1] + $kline[4]) / 2; // Average price (open + close) / 2
        }
        return $sum / $limit; // Moving average
    }

    /**
     * Method to execute trades based on moving averages
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

        // Moving average parameters
        $shortMA = $this->getMovingAverage($symbol, '15m', 5); // Short moving average, 15-minute interval and 5 periods
        $longMA = $this->getMovingAverage($symbol, '15m', 15); // Long moving average, 15-minute interval and 15 periods

        echo "Short Moving Average: $shortMA\n";
        echo "Long Moving Average: $longMA\n";

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

        $quantity = bcdiv($investment, $shortMA, 8); // Amount of cryptocurrency to buy, can change the number of decimal places for greater accuracy

        if ($quantity < $minQty) {
            $quantity = $minQty;
        }

        // Adjusting to step size
        $precision = log10(1 / $stepSize);
        $quantity = floor($quantity / $stepSize) * $stepSize;
        $quantity = number_format($quantity, $precision, '.', '');

        if ($shortMA > $longMA && $usdtBalance >= $investment) {
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
                $quantity = bcdiv($cryptoBalance, '1', 8); // Amount of cryptocurrency to sell, can change the number of decimal places for greater accuracy
                $quantity = floor($quantity / $stepSize) * $stepSize;
                $quantity = number_format($quantity, $precision, '.', '');
                echo "Attempting to sell $quantity BTC\n";
                try {
                    $response = $this->client->newOrder(
                        'BTCUSDT', // Ensure the symbol is correct and fully specified
                        'SELL',
                        'MARKET',
                        [
                            'quantity' => $quantity,
                            'recvWindow' => 60000,
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

    // Method to run the bot continuously
    public function run($symbol, $investment, $intervalSeconds = 60): void
    {
        while (true) {
            try {
                $this->trade($symbol, $investment);
                // Interval parameter, can be changed to control the frequency of trading conditions checks
                sleep($intervalSeconds);
            } catch (\Exception $e) {
                echo "Error in trading loop: " . $e->getMessage() . "\n";
                sleep($intervalSeconds); // Delay on error to avoid spamming
            }
        }
    }
}
