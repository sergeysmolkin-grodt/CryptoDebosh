<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Web;


use App\Application\Services\TradingBotService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TradingBotsController extends AbstractController
{
    private TradingBotService $botService;

    public function __construct(TradingBotService $botService)
    {
        $this->botService = $botService;
    }

    #[Route('/trading-bots', name: 'trading_bots')]
    public function index(): Response
    {
        return $this->render('trading_bots/index.html.twig');
    }

    #[Route('/api/trading-bots/execute', name: 'execute_trading_bot', methods: ['POST'])]
    public function executeBot(Request $request): JsonResponse
    {
        $symbol = $request->request->get('symbol');
        $investment = $request->request->get('investment');

        try {
            $this->botService->runBot($symbol, (float)$investment);
            return new JsonResponse(['status' => 'success']);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}