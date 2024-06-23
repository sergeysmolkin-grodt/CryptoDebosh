<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Web;

use App\Domain\Services\CryptoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CryptoController extends AbstractController
{
    private CryptoService $cryptoService;

    public function __construct(CryptoService $cryptoService)
    {
        $this->cryptoService = $cryptoService;
    }

    /**
     * @Route("/crypto", name="crypto")
     */
    public function index(): Response
    {
        $cryptoRates = $this->cryptoService->getCryptoRates();
        $balance = $this->cryptoService->getBalance();

        return $this->render('crypto/index.html.twig', [
            'cryptoRates' => $cryptoRates,
            'balance' => $balance,
        ]);
    }
}