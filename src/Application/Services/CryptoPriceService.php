<?php

declare(strict_types=1);

namespace CryptoDebosh\Application\Services;

use CryptoDebosh\Domain\Entities\CryptoPrice;
use CryptoDebosh\Infrastructure\Services\BinanceApiService;

class CryptoPriceService
{
    private $binanceApiService;

    public function __construct(BinanceApiService $binanceApiService)
    {
        $this->binanceApiService = $binanceApiService;
    }

    public function getCryptoPrices(): array
    {
        $prices = $this->binanceApiService->getPrices();
        $cryptoPrices = [];

        foreach ($prices as $price) {
            $cryptoPrices[] = new CryptoPrice($price['symbol'], $price['price']);
        }

        return $cryptoPrices;
    }
}