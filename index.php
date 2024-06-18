<?php


// Загружаем bootstrap файл
$cryptoPriceController = require __DIR__ . '/src/bootstrap.php';

// Вызов метода showPrices для отображения цен
$cryptoPriceController->showPrices();
