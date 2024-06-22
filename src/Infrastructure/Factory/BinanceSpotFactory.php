<?php

declare(strict_types=1);

namespace App\Infrastructure\Factory;

use Binance\Spot;
use GuzzleHttp\Client;

class BinanceSpotFactory
{
    public static function create(string $key, string $secret): Spot
    {
        $guzzleClient = new Client([
            'verify' => false,
            'debug' => true,
        ]);

        return new Spot([
            'key' => $key,
            'secret' => $secret,
            'http_client_handler' => $guzzleClient,
            'recvWindow' => 60000,
        ]);
    }
}