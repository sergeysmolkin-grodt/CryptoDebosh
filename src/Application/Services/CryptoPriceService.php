<?php

declare(strict_types=1);

namespace CryptoDebosh\Application\Services;

use CryptoDebosh\Domain\Entities\CryptoPrice;
use CryptoDebosh\Infrastructure\Services\BinanceApiService;

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