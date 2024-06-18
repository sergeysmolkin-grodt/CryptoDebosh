<?php

use CryptoDebosh\Application\Services\CryptoPriceService;
use CryptoDebosh\Infrastructure\Services\BinanceApiService;
use CryptoDebosh\Presentation\Controllers\CryptoPriceController;

require __DIR__ . '/../vendor/autoload.php';



$config = require __DIR__ . '/../config/config.php';

$binanceApiService = new BinanceApiService($config['binance']['apiKey'], $config['binance']['secretKey']);
$cryptoPriceService = new CryptoPriceService($binanceApiService);
$cryptoPriceController = new CryptoPriceController($cryptoPriceService);

return $cryptoPriceController;
