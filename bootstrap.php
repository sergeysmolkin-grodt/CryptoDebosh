<?php

require 'vendor/autoload.php';

use Symfony\Component\Console\Application;
use CryptoDebosh\Application\Services\TradingBotService;
use CryptoDebosh\Presentation\Commands\TradingBotCommand;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$config = require __DIR__ . '/config/config.php';

$tradingBotService = new TradingBotService(
    $config['binance']['api_key'],
    $config['binance']['secret_key']
);

$application = new Application('CryptoDebosh', '1.0.0');

$application->add(new TradingBotCommand($tradingBotService));

try {
    $application->run();
} catch (Exception $e) {
}
