<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeleteUserController extends AbstractController
{
    #[Route('/delete/user', name: 'app_delete_user')]
    public function index(UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $isGranted = $this->isGranted('ROLE_ADMIN');

        $users = $userRepository->findAll();


        $userId = $request->get('id');
        if(!empty($userId)) {
            $userToDelete = $userRepository->find($userId);
            $entityManager->remove($userToDelete);
            $entityManager->flush();

            $this->addFlash('notice', 'The user has been deleted');

            return $this->redirectToRoute('app_for_admins');
        }

        return $this->render('delete_user/index.html.twig', [
            'controller_name' => 'DeleteUserController',
            'isGranted' => $isGranted,
            'users' => $users
        ]);
    }
}
