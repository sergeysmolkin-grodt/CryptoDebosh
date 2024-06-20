<?php

require __DIR__ . '/vendor/autoload.php';

use CryptoDebosh\Application\Services\BlockchainService;
use CryptoDebosh\Presentation\Commands\BlockchainCommand;
use Symfony\Component\Console\Application;

$blockchainService = new BlockchainService();

$application = new Application();
$application->add(new BlockchainCommand($blockchainService));

return $application;