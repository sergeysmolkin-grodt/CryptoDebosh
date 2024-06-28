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

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use App\Presentation\Controller\Web\FirstPageController;

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
};
