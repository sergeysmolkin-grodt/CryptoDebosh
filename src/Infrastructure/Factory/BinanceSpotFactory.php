<?php

declare(strict_types=1);

namespace App\Infrastructure\Factory;

use Binance\Spot;
use GuzzleHttp\Client;

class BinanceSpotFactory
{
    public static function create(string $apiKey, string $secretKey, Client $httpClientHandler): Spot
    {
        return new Spot($apiKey, $secretKey, $httpClientHandler);
    }
}
