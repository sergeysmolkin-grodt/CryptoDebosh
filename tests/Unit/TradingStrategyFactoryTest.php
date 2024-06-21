<?php

declare(strict_types=1);

namespace App\Tests\Unit;

class TradingStrategyFactoryTest extends TestCase
{
    public function testCreateMovingAverageStrategy()
    {
        $factory = new TradingStrategyFactory();
        $strategy = $factory->create('moving_average', 'BTCUSDT', 100);
        $this->assertInstanceOf(MovingAverageStrategy::class, $strategy);
    }

    public function testCreateInvalidStrategy()
    {
        $this->expectException(\InvalidArgumentException::class);
        $factory = new TradingStrategyFactory();
        $factory->create('invalid_strategy', 'BTCUSDT', 100);
    }
}