<?php

/*
 * config/services.php
 *
 * This file is used to define services for the application.
 *
 * The services defined here are automatically registered with the Symfony service container.
 *
 *
 */

// config/services.php

// config/services.php

// config/services.php

use App\Application\Contracts\TradingBots\TradingStrategyInterface;
use App\Application\Services\TradingBots\Spot\MovingAverageStrategy;
use App\Application\Services\TradingBotService;
use App\Infrastructure\Services\BinanceSpot;
use App\Presentation\Controller\Web\TradingBotsController;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use App\Presentation\Controller\Web\FirstPageController;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\Reference;

return function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire(true)
        ->autoconfigure(true)
        ->public();

    $services->load('App\\Presentation\\Controller\\', '../src/Presentation/Controller/')
        ->tag('controller.service_arguments')
        ->public();

    $services->set(FirstPageController::class)
        ->public()
        ->tag('controller.service_arguments');

    $services->set(Client::class);

    $parameters = $containerConfigurator->parameters();
    $parameters->set('binance_api_key', '%env(BINANCE_API_KEY)%');
    $parameters->set('binance_api_secret', '%env(BINANCE_SECRET_KEY)%');

    $services->set(BinanceSpot::class)
        ->arg('$key', '%env(BINANCE_API_KEY)%')
        ->arg('$secret', '%env(BINANCE_SECRET_KEY)%')
        ->arg('$httpClient', new Reference(Client::class));

    // Domain Layer
    $services->set(MovingAverageStrategy::class)
        ->arg('$client', new Reference(BinanceSpot::class));

    // Application Layer
    $services->set(TradingStrategyInterface::class, MovingAverageStrategy::class);

    $services->set(TradingBotService::class)
        ->arg('$key', '%env(BINANCE_API_KEY)%')
        ->arg('$secret', '%env(BINANCE_SECRET_KEY)%')
        ->arg('$client', new Reference(BinanceSpot::class))
        ->arg('$strategy', new Reference(MovingAverageStrategy::class));

    // Controller
    $services->set(TradingBotsController::class)
        ->arg('$botService', new Reference(TradingBotService::class));
};
