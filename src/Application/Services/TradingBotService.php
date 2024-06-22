<?php

namespace App\Application\Services;

use App\Infrastructure\Services\BinanceSpot;
use App\Application\Contracts\TradingStrategyInterface;

class TradingBotService
{
    private string $key;
    private string $secret;
    private BinanceSpot $client; // Updated to BinanceSpot
    private TradingStrategyInterface $strategy;

    public function __construct(string $key, string $secret, BinanceSpot $client, TradingStrategyInterface $strategy)
    {
        $this->key = $key;
        $this->secret = $secret;
        $this->client = $client;
        $this->strategy = $strategy;
    }

    public function trade(string $symbol, float $investment): void
    {
        $this->strategy->execute($symbol, $investment);
    }

    public function setStrategy(TradingStrategyInterface $strategy): void
    {
        $this->strategy = $strategy;
    }

    public function run(string $symbol, float $investment, int $intervalSeconds): void
    {
        while (true) {
            $this->trade($symbol, $investment);
            sleep($intervalSeconds);
        }
    }

    public function stop(): void
    {
        // Implementation to stop the bot
    }
}