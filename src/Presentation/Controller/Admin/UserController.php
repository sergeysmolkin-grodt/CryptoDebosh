<?php

namespace App\Presentation\Controller\Admin;

use App\Infrastructure\Persistence\Doctrine\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/admin/users", name="admin_users")
     */
    public function index(): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Access denied.');
        }

        $users = $this->userRepository->findAllUsers();

        return $this->render('admin/users/index.html.twig', [
            'users' => $users,
        ]);
    }
}
