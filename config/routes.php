<?php
// config/routes.php
use App\Presentation\Controller\Web\FirstPageController;
use App\Presentation\Controller\Web\TradingBotsController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->add('homepage', '/')
        ->controller([FirstPageController::class, 'index'])
        ->methods(['GET']);

    $routes->add('trading-bots', '/trading-bots')
        ->controller([TradingBotsController::class, 'index'])
        ->methods(['GET']);

};