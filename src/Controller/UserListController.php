<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserListController extends AbstractController
{
    #[Route('home/for-admins/users-list', name: 'app_users_list')]
    public function index(UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $isGranted = $this->isGranted('ROLE_ADMIN');

        $users = $userRepository->findAll();

        return $this->render('user_list/index.html.twig', [
            'controller_name' => 'UserListController',
            'isGranted' => $isGranted,
            'users' => $users
        ]);
    }
}
