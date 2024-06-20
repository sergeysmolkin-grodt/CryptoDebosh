<?php

declare(strict_types=1);

namespace CryptoDebosh\Application\Services;

use CryptoDebosh\Infrastructure\Services\BinanceApiService;

class TradeService
{
    private $binanceApiService;

    public function __construct(BinanceApiService $binanceApiService)
    {
        $this->binanceApiService = $binanceApiService;
    }

    public function executeTrade($symbol, $side, $quantity)
    {
        $response = $this->binanceApiService->newOrder($symbol, $side, $quantity);
        $trade = new Trade($response['orderId'], $symbol, $side, $quantity, $response['fills'][0]['price']);

        return $trade;
    }
}