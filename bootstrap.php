<?php


require __DIR__ . '/../vendor/autoload.php';

use Application\Services\TradeService;
use CryptoDebosh\Infrastructure\Services\BinanceApiService;
use CryptoDebosh\Application\Services\CryptoPriceService;
use CryptoDebosh\Presentation\Commands\GetCryptoPricesCommand;
use CryptoDebosh\Presentation\Commands\ExecuteTradeCommand;
use Symfony\Component\Console\Application;

$config = require __DIR__ . '/../config/config.php';

$binanceApiService = new BinanceApiService($config['binance']['apiKey'], $config['binance']['secretKey']);
$cryptoPriceService = new CryptoPriceService($binanceApiService);
$tradeService = new TradeService($binanceApiService);

$application = new Application();
$application->add(new GetCryptoPricesCommand($cryptoPriceService));
$application->add(new ExecuteTradeCommand($tradeService));

return $application;
