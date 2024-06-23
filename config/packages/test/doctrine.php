<?php


use Symfony\Config\DoctrineConfig;

return static function (DoctrineConfig $config): void {
    $dbal = $config->dbal();
    $dbal->dbnameSuffix('_test%env(default::TEST_TOKEN)%');
};