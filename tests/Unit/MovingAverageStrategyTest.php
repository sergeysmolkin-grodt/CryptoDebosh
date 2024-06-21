<?php

declare(strict_types=1);

namespace App\Tests\Unit;

class MovingAverageStrategyTest extends TestCase
{
    private $strategy;

    protected function setUp(): void
    {
        $apiKey = 'test_api_key';
        $secretKey = 'test_secret_key';

        $client = new Spot([
            'key' => $apiKey,
            'secret' => $secretKey,
            'http_client_handler' => new Client([
                'verify' => false,
                'debug' => true,
            ]),
            'recvWindow' => 60000,
        ]);

        $this->strategy = new MovingAverageStrategy($client);
    }

    public function testCalculateRSI()
    {
        $rsi = $this->strategy->calculateRSI('BTCUSDT', '15m');
        $this->assertIsFloat($rsi);
    }

    public function testGetMovingAverage()
    {
        $ma = $this->strategy->getMovingAverage('BTCUSDT', '15m', 20);
        $this->assertIsFloat($ma);
    }
}