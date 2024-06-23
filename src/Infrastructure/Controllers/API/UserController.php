<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\API;

use App\Infrastructure\Persistence\Doctrine\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/users", name="user_list")
     */
    public function list(): Response
    {
        $users = $this->userRepository->findAll();

        return $this->render('user/list.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/user/new", name="user_new")
     */
    public function new(Request $request): Response
    {
        // Логика для создания нового пользователя
        return $this->render('user/new.html.twig');
    }

    /**
     * @Route("/user/{id}", name="user_show")
     */
    public function show(int $id): Response
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }
}
