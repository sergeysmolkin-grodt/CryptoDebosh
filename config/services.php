<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use App\Infrastructure\Persistence\Doctrine\UserRepository;
use Doctrine\Persistence\ManagerRegistry;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(UserRepository::class)
        ->arg('$registry', service(ManagerRegistry::class));

    // Load all services in src/
    $services->load('App\\', '../src/*')
        ->exclude('../src/{DependencyInjection,Entity,Migrations,Tests,Kernel.php}');

    // Other services configuration...
};
