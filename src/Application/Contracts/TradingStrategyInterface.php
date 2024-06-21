<?php

namespace App\Application\Contracts;

interface TradingStrategyInterface
{
    public function execute(string $symbol, float $investment): void;
}