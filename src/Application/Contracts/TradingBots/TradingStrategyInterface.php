<?php

namespace App\Application\Contracts\TradingBots;

interface TradingStrategyInterface
{
    public function execute(string $symbol, float $investment): void;
}