<?php

namespace App\Application\Services;

use App\Application\Contracts\TradingStrategyInterface;
use App\Application\Factories\TradingStrategyFactory;
use Binance\Spot;

class TradingBotService
{
    private string $key;
    private string $secret;
    private Spot $client;
    private TradingStrategyInterface $strategy;

    public function __construct(string $key, string $secret, Spot $client, TradingStrategyInterface $strategy)
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

    public function run(string $symbol, float $investment, int $intervalSeconds): void
    {
        while (true) {
            $this->trade($symbol, $investment);
            sleep($intervalSeconds);
        }
    }

    public function stop(): void
    {
        // Реализация остановки бота
    }
}
