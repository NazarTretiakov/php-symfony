<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class YourProfileController extends AbstractController
{
    #[Route('home/your-profile', name: 'app_your_profile')]
    public function index(UserRepository $userRepository, EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $isGranted = $this->isGranted('ROLE_ADMIN');


        $user = $this->getUser();

        $newName = $request->get('name');
        $newLastname = $request->get('lastname');
        $newPassword = $request->get('password');


        if(!empty($newName) || !empty($newLastname) || !empty($newPassword)) {

            if(!empty($newName)) {
                $user->setName($newName);
            }
            if(!empty($newLastname)) {
                $user->setLastname($newLastname);
            }

            if(!empty($newPassword)) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $newPassword
                    )
                );
            }

            $entityManager->flush();

            $this->addFlash('notice', 'Information of your profile has been changed');

            return $this->redirectToRoute('app_home');
        }


        return $this->render('your_profile/index.html.twig', [
            'controller_name' => 'YourProfileController',
            'isGranted' => $isGranted,
            'user' => $this->getUser()
        ]);
    }
}
