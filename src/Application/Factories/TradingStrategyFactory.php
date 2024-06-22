<?php

declare(strict_types=1);

namespace App\Application\Factories;

use App\Application\Contracts\TradingStrategyInterface;
use Binance\Spot;

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