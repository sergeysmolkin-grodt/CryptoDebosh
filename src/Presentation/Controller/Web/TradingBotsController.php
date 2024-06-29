<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Web;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TradingBotsController extends AbstractController
{
    #[Route('/trading-bots', name: 'trading_bots')]
    public function index(): Response
    {
        error_log('TradingBotsController@index вызван');
        return $this->render('trading_bots/index.html.twig');
    }
}