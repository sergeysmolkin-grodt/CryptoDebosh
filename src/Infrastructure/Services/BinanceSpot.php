<?php

declare(strict_types=1);
namespace App\Infrastructure\Services;
// src/Infrastructure/Services/BinanceSpot.php


// src/Infrastructure/Services/BinanceSpot.php

namespace App\Infrastructure\Services;

use GuzzleHttp\Client;

class BinanceSpot
{
    private string $key;
    private string $secret;
    private Client $httpClient;

    public function __construct(string $key, string $secret, Client $httpClient)
    {
        $this->key = $key;
        $this->secret = $secret;
        $this->httpClient = $httpClient;
    }

    public function account(array $params): array
    {
        // Implement the API call to get account information
        $response = $this->httpClient->request('GET', 'https://api.binance.com/api/v3/account', [
            'headers' => [
                'X-MBX-APIKEY' => $this->key,
            ],
            'query' => array_merge($params, [
                'timestamp' => round(microtime(true) * 1000),
                'signature' => $this->generateSignature($params)
            ])
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    private function generateSignature(array $params): string
    {
        return hash_hmac('sha256', http_build_query($params), $this->secret);
    }
}
