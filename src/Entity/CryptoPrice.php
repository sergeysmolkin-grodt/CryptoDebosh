<?php

declare(strict_types=1);

namespace App\Entity;

final class CryptoPrice
{
    private $symbol;
    private $price;

    public function __construct($symbol, $price)
    {
        $this->symbol = $symbol;
        $this->price = $price;
    }

    public function getSymbol()
    {
        return $this->symbol;
    }

    public function getPrice()
    {
        return $this->price;
    }
}