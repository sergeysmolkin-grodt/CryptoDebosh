<?php

use DI\ContainerBuilder;
use CryptoDebosh\Application\Services\TradingBotService;
use CryptoDebosh\Presentation\Commands\TradingBotCommand;

return function () {
    $builder = new ContainerBuilder();

    $config = require __DIR__ . '/config/config.php';

    $builder->addDefinitions([
        TradingBotService::class => DI\autowire()->constructorParameter('key', $config['binance']['api_key'])->constructorParameter('secret', $config['binance']['secret_key']),
        TradingBotCommand::class => DI\autowire(),
    ]);

    return $builder->build();
};
