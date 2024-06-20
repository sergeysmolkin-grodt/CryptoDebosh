<?php

require __DIR__ . '/vendor/autoload.php';


use CryptoDebosh\Application\Services\TradingBotService;
use CryptoDebosh\Presentation\Commands\TradingBotCommand;
use Symfony\Component\Console\Application;

$config = require __DIR__ . '/config/config.php';
$tradingBotService = new TradingBotService($config['binance']['apiKey'], $config['binance']['secretKey']);


$application = new Application();
$application->add(new TradingBotCommand($tradingBotService));

return $application;