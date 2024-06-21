<?php

namespace CryptoDebosh\Application\Services;

use Binance\Spot;
use CryptoDebosh\Application\Factories\TradingStrategyFactory;

class TradingBotService
{
    private string $key;
    private string $secret;
    private Spot $client;
    private $strategy;

    public function __construct(string $key, string $secret)
    {
        $this->key = $key;
        $this->secret = $secret;

        $guzzleClient = new \GuzzleHttp\Client([
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
