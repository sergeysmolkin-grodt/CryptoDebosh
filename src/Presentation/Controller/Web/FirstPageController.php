<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class FirstPageController extends AbstractController
{
    #[Route(
         path: '/',
         name: 'homepage',
         methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('main/first-page.index.html.twig');
    }

    #[Route(
        path: '/other-page',
        name: 'homepage',
        methods: ['GET'])]
    public function otherPage()
    {
        return $this->render('main/first-page.index.html.twig');
    }

}