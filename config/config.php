<?php

return [
    'name' => 'CryptoDebosh',
    'version' => '1.0.0',
    'binance' => [
        'api_key' => getenv('BINANCE_API_KEY'),
        'secret_key' => getenv('BINANCE_SECRET_KEY'),
        'recv_window' => 60000,
    ],
    'log_file' => __DIR__ . '/../logs/bot.log',
];
