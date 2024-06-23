<?php


use Symfony\Config\MonologConfig;

return static function (MonologConfig $config): void {
    $mainHandler = $config->handler('main');
    $mainHandler->type('stream')
        ->path('%kernel.logs_dir%/%kernel.environment%.log')
        ->level('debug');

    $consoleHandler = $config->handler('console');
    $consoleHandler->type('console')
        ->processPsr3Messages('false')
        ->level('info');

    $config->channels(['!event', '!doctrine']);

    $config->handler('file')
        ->type('stream')
        ->path('%kernel.logs_dir%/app.log')
        ->level('error');

    $config->handler('rotating_file')
        ->type('rotating_file')
        ->path('%kernel.logs_dir%/app_rotating.log')
        ->maxFiles(1)
        ->level('info');

};
