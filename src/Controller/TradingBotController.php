<?php

declare(strict_types=1);

// src/Controller/TradingBotController.php

namespace App\Controller;

use App\Application\Services\TradingBotService;
use App\Application\Factories\TradingStrategyFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TradingBotController extends AbstractController
{
    private TradingBotService $tradingBotService;
    private TradingStrategyFactory $strategyFactory;

    public function __construct(TradingBotService $tradingBotService, TradingStrategyFactory $strategyFactory)
    {
        $this->tradingBotService = $tradingBotService;
        $this->strategyFactory = $strategyFactory;
    }

    public function index(): Response
    {
        return $this->render('trading_bots/index.html.twig');
    }

    public function run(Request $request): Response
    {
        $symbol = $request->request->get('symbol');
        $investment = (float) $request->request->get('investment');
        $strategyName = $request->request->get('strategy', 'moving_average');

        $strategy = $this->strategyFactory->create($strategyName);
        $this->tradingBotService->setStrategy($strategy);
        $this->tradingBotService->trade($symbol, $investment);

        return new Response('Trading bot started.');
    }
}
