<?php

declare(strict_types=1);

namespace CryptoDebosh\Infrastructure\Services;

use Binance\Spot;

class BinanceApiService
{
    private $client;

    public function __construct($apiKey, $secretKey)
    {
        $this->client = new Spot($apiKey, $secretKey);
    }

    public function getPrices()
    {
        return $this->client->tickerPrice();
    }
}