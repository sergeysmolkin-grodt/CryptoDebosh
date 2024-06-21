<?php

namespace CryptoDebosh\Application\Services;

use Binance\Spot;
use CryptoDebosh\Application\Factories\TradingStrategyFactory;

class TradingBotService
{
    private Spot $client;
    private $strategy;

    public function __construct($key, $secret, $strategyName)
    {
        $this->client = new Spot([
            'key' => trim($key),
            'secret' => trim($secret),
        ]);

        $this->strategy = TradingStrategyFactory::create($strategyName, $this->client);
    }

    public function trade($symbol, $investment)
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
