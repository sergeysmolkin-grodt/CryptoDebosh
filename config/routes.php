<?php
// config/routes.php

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;


return function (RoutingConfigurator $routes) {
    $routes->add('homepage', '/')
        ->controller([App\Presentation\Controller\Web\FirstPageController::class, 'index']);
};