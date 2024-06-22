<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Application\Factories\TradingStrategyFactory;
use Binance\Spot;
use GuzzleHttp\Client;

class TradingBotService
{
    private string $key;
    private string $secret;
    private Spot $client;
    private $strategy;

    public function __construct(string $key, string $secret, $strategy)
    {
        $this->key = $key;
        $this->secret = $secret;
        $this->strategy = $strategy;

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

    public function setStrategy($strategyName): void
    {
        $this->strategy = TradingStrategyFactory::create($strategyName, $this->client);
    }

    public function trade($symbol, $investment): void
    {
        $this->strategy->execute($symbol, $investment);
    }

    public function run($symbol, $investment, $intervalSeconds = 60): void
    {
        while (true) {
            try {
                $this->trade($symbol, $investment);
            } catch (\Exception $e) {
                echo "Error in trading loop: " . $e->getMessage() . "\n";
            }
            sleep($intervalSeconds);
        }
    }
}