<?php

namespace CryptoDebosh\Application\Factories;

use Binance\Spot;
use CryptoDebosh\Application\Contracts\TradingStrategyInterface;
use CryptoDebosh\Application\Services\TradingStrategies\MovingAverageStrategy;

class TradingStrategyFactory
{
    public static function create($strategyName, Spot $client): TradingStrategyInterface
    {
        return match ($strategyName) {
             'moving_average' => new MovingAverageStrategy($client),
             default => throw new \Exception("Unknown trading strategy: $strategyName"),
        };
    }
}
