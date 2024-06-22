<?php

declare(strict_types=1);

// src/Controller/TradingBotController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TradingBotController
{
    /**
     * @Route("/trading-bot", name="trading_bot")
     */
    public function index(): Response
    {
        return new Response(
            '<html><body>Trading Bot Interface</body></html>'
        );
    }
}
