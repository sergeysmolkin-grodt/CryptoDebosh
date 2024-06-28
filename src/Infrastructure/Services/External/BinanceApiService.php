<?php

declare(strict_types=1);

namespace App\Infrastructure\Services\External;

use Binance\Exception\MissingArgumentException;
use Binance\Spot;

class BinanceApiService
{
    private $apiKey;
    private $apiSecret;
    private $client;

    public function __construct(string $apiKey, string $apiSecret)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->client = new Spot(['apiKey' => $this->apiKey, 'secretKey' => $this->apiSecret]);
    }

    public function getPrices()
    {
        $prices = $this->client->tickerPrice();
        $formattedPrices = [];

        foreach ($prices as $price) {
            $formattedPrices[] = [
                'symbol' => $price['symbol'],
                'price' => $price['price'],
            ];
        }

        return $formattedPrices;
    }

    /**
     * @throws MissingArgumentException
     */
    public function placeOrder($symbol, $side, $type, $quantity, $price = null)
    {
        $params = [
            'quantity' => $quantity,
        ];

        if ($price !== null) {
            $params['price'] = $price;
        }

        return $this->client->newOrder($symbol, $side, $type, $params);
    }

    public function getHistoricalData($symbol, $interval, $startTime)
    {
        return $this->client->klines($symbol, $interval, [
            'startTime' => $startTime
        ]);
    }
}