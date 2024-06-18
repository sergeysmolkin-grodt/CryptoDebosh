<?php

declare(strict_types=1);

namespace CryptoDebosh\Presentation\Controllers;

use CryptoDebosh\Application\Services\CryptoPriceService;

class CryptoPriceController
{
    private CryptoPriceService $cryptoPriceService;

    public function __construct(CryptoPriceService $cryptoPriceService)
    {
        $this->cryptoPriceService = $cryptoPriceService;
    }

    public function showPrices(): void
    {
        $prices = $this->cryptoPriceService->getCryptoPrices();
        foreach ($prices as $price) {
            echo $price->getSymbol() . ': ' . $price->getPrice() . PHP_EOL;
        }
    }
}