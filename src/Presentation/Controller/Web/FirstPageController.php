<?php

declare(strict_types=1);

// src/Presentation/Controller/Web/FirstPageController.php

namespace App\Presentation\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FirstPageController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('main/first-page.index.html.twig');
    }
}