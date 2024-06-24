<?php

declare(strict_types=1);

namespace App\Infrastructure\Services\External;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CoinbaseApiService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getPrices(): array
    {
        $coins = $this->getCoins();
        $formattedPrices = [];

        foreach ($coins as $coin) {
            try {
                $response = $this->client->request('GET', "https://api.coinbase.com/v2/prices/{$coin}-USD/spot");
                $data = $response->toArray();

                $formattedPrices[] = [
                    'symbol' => $coin . 'USDT',
                    'price' => $data['data']['amount'],
                ];
            } catch (\Exception $e) {
                // Handle exception
            }
        }

        return $formattedPrices;
    }

    public function getCoins()
    {
        $coins = ['BTC', 'ETH', 'LTC', 'XRP', 'BCH'];

        return $coins;
    }
}