<?php

// src/Application/Factories/TradingStrategyFactory.php

namespace App\Application\Factories;

use App\Application\Contracts\TradingStrategyInterface;
use InvalidArgumentException;

class TradingStrategyFactory
{
    private array $strategies;

    public function __construct(array $strategies)
    {
        $this->strategies = $strategies;
    }

    public function create(string $strategyName): TradingStrategyInterface
    {
        if (!isset($this->strategies[$strategyName])) {
            throw new InvalidArgumentException("Strategy {$strategyName} not found.");
        }

        return $this->strategies[$strategyName];
    }
}
