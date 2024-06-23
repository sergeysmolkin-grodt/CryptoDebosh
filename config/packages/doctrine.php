<?php

use Symfony\Config\DoctrineConfig;

return static function (DoctrineConfig $config): void {
    $dbal = $config->dbal();
    $dbal->driver('pdo_pgsql')
        ->serverVersion('13')
        ->charset('utf8')
        ->url('%env(resolve:DATABASE_URL)%');

    $orm = $config->orm();
    $orm->autoGenerateProxyClasses(true)
        ->enableLazyGhostObjects(true)
        ->reportFieldsWhereDeclared(true)
        ->validateXmlMapping(true)
        ->namingStrategy('doctrine.orm.naming_strategy.underscore_number_aware')
        ->autoMapping(true);

    $mapping = $orm->mapping('App');
    $mapping->type('attribute')
        ->isBundle(false)
        ->dir('%kernel.project_dir%/src/Domain/Entities')
        ->prefix('App\Domain\Entities')
        ->alias('App');

    $orm->controllerResolver()
        ->autoMapping(false);
};

