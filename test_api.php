<?php

require 'vendor/autoload.php';

use Binance\Spot;
use GuzzleHttp\Client;

$apiKey = 'AngXKuIxLI8g9QAmyWQpgPwABXuLBU8WfLLZtlREGBQjivaLxBspjXQ2l2ba0Es';
$secretKey = '3uTMpQy6K17quu2x1cPqhx8Dr12VoZWBOcZAUwbdfP2ACr5ZBAGbIfuPgWxqlZCh';

$guzzleClient = new Client([
    'verify' => false,
    'debug' => true,
]);

$client = new Spot([
    'key' => $apiKey,
    'secret' => $secretKey,
    'http_client_handler' => $guzzleClient,
    'recvWindow' => 60000,
    'options' => [
        'adjustForTimeDifference' => true
    ]
]);

try {
    $accountInfo = $client->account(['recvWindow' => 60000]);
    echo "Account Info: " . json_encode($accountInfo) . "\n";
} catch (\Exception $e) {
    echo "Error fetching account info: " . $e->getMessage() . "\n";
}

try {
    $response = $client->newOrder([
        'symbol' => 'BTCUSDT',
        'side' => 'BUY',
        'type' => 'MARKET',
        'quantity' => 0.0001
    ]);
    echo "Purchased BTC. Response: " . json_encode($response) . "\n";
} catch (\Exception $e) {
    echo "Error executing buy order: " . $e->getMessage() . "\n";
}

try {
    $response = $client->newOrder([
        'symbol' => 'BTCUSDT',
        'side' => 'SELL',
        'type' => 'MARKET',
        'quantity' => 0.0001
    ]);
    echo "Sold BTC. Response: " . json_encode($response) . "\n";
} catch (\Exception $e) {
    echo "Error executing sell order: " . $e->getMessage() . "\n";
}
