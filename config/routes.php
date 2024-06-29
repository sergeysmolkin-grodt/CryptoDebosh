<?php
// config/routes.php
use App\Presentation\Controller\Web\FirstPageController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->add('homepage', '/')
        ->controller([FirstPageController::class, 'index'])
        ->methods(['GET']);
};