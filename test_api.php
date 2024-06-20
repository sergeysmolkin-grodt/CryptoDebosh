<?php

require 'vendor/autoload.php';

use Binance\Spot;
use GuzzleHttp\Client;

$config = require 'config/config.php';

$apiKey = trim($config['binance']['apiKey']);
$secretKey = trim($config['binance']['secretKey']);

$guzzleClient = new Client([
    'verify' => false, // Отключить проверку SSL для тестирования
    'debug' => true,   // Включить отладочную информацию
]);

$client = new Spot([
    'apiKey' => $apiKey,
    'secret' => $secretKey,
    'http_client_handler' => $guzzleClient,
    'recvWindow' => 60000,
    'options' => [
        'adjustForTimeDifference' => true
    ]
]);

try {
    $response = $client->account();
    print_r($response);
} catch (Exception $e) {
    echo 'Ошибка: ',  $e->getMessage(), "\n";
    if ($e instanceof \GuzzleHttp\Exception\ClientException) {
        $response = $e->getResponse();
        echo 'Ответ: ', $response->getBody()->getContents(), "\n";
    }
}
