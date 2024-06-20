<?php

require __DIR__ . '/vendor/autoload.php';

use CryptoDebosh\Infrastructure\Services\BinanceApiService;
use CryptoDebosh\Application\Services\CryptoPriceService;
use CryptoDebosh\Presentation\Commands\GetCryptoPricesCommand;
use Symfony\Component\Console\Application;

$config = require __DIR__ . '/config/config.php';

$binanceApiService = new BinanceApiService($config['binance']['apiKey'], $config['binance']['secretKey']);
$cryptoPriceService = new CryptoPriceService($binanceApiService);

$application = new Application();
$application->add(new GetCryptoPricesCommand($cryptoPriceService));
var_dump($application);
return $application;
