<?php

declare(strict_types=1);

namespace App\Domain\Entities;

class Trade
{
    private string $symbol;
    private float $investment;

    public function __construct(string $symbol, float $investment)
    {
        $this->symbol = $symbol;
        $this->investment = $investment;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getInvestment(): float
    {
        return $this->investment;
    }
}