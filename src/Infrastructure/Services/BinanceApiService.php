<?php

declare(strict_types=1);

namespace CryptoDebosh\Infrastructure\Services;

use Binance\Spot;
use GuzzleHttp\Client;

class BinanceApiService
{
    private $client;

    public function __construct($apiKey, $secretKey)
    {
        $guzzleClient = new Client([
            'verify' => false,
        ]);

        $this->client = new Spot([
            'apiKey' => $apiKey,
            'secretKey' => $secretKey,
            'http_client_handler' => $guzzleClient, // Указание кастомного клиента Guzzle

        ]);
    }

    public function getPrices()
    {
        return $this->client->tickerPrice();
    }
}