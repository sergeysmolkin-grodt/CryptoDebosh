<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Application\Contracts\TradingStrategyInterface;
use App\Application\Factories\TradingStrategyFactory;
use Binance\Spot;
use GuzzleHttp\Client;

class TradingBotService
{
    private string $key;
    private string $secret;
    private Spot $client;
    private TradingStrategyInterface $strategy;

    public function __construct(string $key, string $secret, TradingStrategyInterface $strategy)
    {
        $this->key = $key;
        $this->secret = $secret;
        $this->strategy = $strategy;

        $this->client = new Spot([
            'key' => $this->key,
            'secret' => $this->secret,
        ]);
    }

    public function setStrategy($strategyName): void
    {
        $this->strategy = TradingStrategyFactory::create($strategyName, $this->client);
    }

    public function trade(string $symbol, float $investment, string $strategyName): void
    {
        try {
            $this->strategy->execute($symbol, $investment);
        } catch (\Exception $e) {
            echo "Error executing trade: " . $e->getMessage() . "\n";
        }
    }
    public function run(string $symbol, float $investment, string $strategyName, int $intervalSeconds = 1): void
    {
        while (true) {
            try {
                $this->trade($symbol, $investment, $strategyName);
                sleep($intervalSeconds);
            } catch (\Exception $e) {
                echo "Error in trading loop: " . $e->getMessage() . "\n";
                sleep($intervalSeconds); // Delay on error to avoid spamming
            }
        }
    }
}