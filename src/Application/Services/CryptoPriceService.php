<?php

declare(strict_types=1);

namespace App\Application\Services;

class CryptoPriceService
{
    private BinanceApiService $binanceApiService;

    public function __construct(BinanceApiService $binanceApiService)
    {
        $this->binanceApiService = $binanceApiService;
    }

    public function getCryptoPrices()
    {
        return $this->binanceApiService->getPrices();
    }
}