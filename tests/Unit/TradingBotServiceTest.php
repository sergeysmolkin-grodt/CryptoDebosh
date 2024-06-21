<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use CryptoDebosh\Application\Services\TradingBotService;
use Binance\Spot;

class TradingBotServiceTest extends TestCase
{
    private TradingBotService $tradingBotService;
    private $spotMock;

    protected function setUp(): void
    {
        $apiKey = 'test_api_key';
        $secretKey = 'test_secret_key';

        $this->spotMock = $this->createMock(Spot::class);

        $this->tradingBotService = new TradingBotService($apiKey, $secretKey);
    }

    public function testGetMovingAverage()
    {
        $symbol = 'BTCUSDT';
        $interval = '1m';
        $limit = 5;
        $klines = [
            [0, 65000, 0, 0, 65100, 0, 0, 0, 0, 0, 0, 0],
            [0, 65100, 0, 0, 65200, 0, 0, 0, 0, 0, 0, 0],
            [0, 65200, 0, 0, 65300, 0, 0, 0, 0, 0, 0, 0],
            [0, 65300, 0, 0, 65400, 0, 0, 0, 0, 0, 0, 0],
            [0, 65400, 0, 0, 65500, 0, 0, 0, 0, 0, 0, 0],
        ];

        $this->spotMock->expects($this->once())
            ->method('klines')
            ->with($symbol, $interval, ['limit' => $limit])
            ->willReturn($klines);

        $reflection = new \ReflectionClass($this->tradingBotService);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($this->tradingBotService, $this->spotMock);

        $result = $this->tradingBotService->getMovingAverage($symbol, $interval, $limit);
        $this->assertEquals(65250, $result);
    }

    public function testTradeNoUSDTBalance()
    {
        $symbol = 'BTCUSDT';
        $investment = 25;
        $accountInfo = [
            'balances' => [
                ['asset' => 'BTC', 'free' => '0.1', 'locked' => '0.0'],
                // No USDT balance
            ]
        ];

        $this->spotMock->expects($this->once())
            ->method('account')
            ->with(['recvWindow' => 60000])
            ->willReturn($accountInfo);

        $reflection = new \ReflectionClass($this->tradingBotService);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($this->tradingBotService, $this->spotMock);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('USDT balance not found.');

        $this->tradingBotService->trade($symbol, $investment);
    }
}
