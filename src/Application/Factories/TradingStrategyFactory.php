<?php

namespace CryptoDebosh\Application\Factories;

use Binance\Spot;
use CryptoDebosh\Application\Services\TradingStrategies\MovingAverageStrategy;

class TradingStrategyFactory
{
    public static function create($strategyName, Spot $client)
    {
        switch ($strategyName) {
            case 'moving_average':
                return new MovingAverageStrategy($client);
            // Add other strategies here
            default:
                throw new \Exception("Unknown trading strategy: $strategyName");
        }
    }
}
