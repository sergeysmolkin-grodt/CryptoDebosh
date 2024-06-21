<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;

$container = (require __DIR__ . '/di.php')();

$application = new Application();
$application->add($container->get(CryptoDebosh\Presentation\Commands\TradingBotCommand::class));

$application->run();
