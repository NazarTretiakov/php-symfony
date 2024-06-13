<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ForAdminsController extends AbstractController
{
    #[Route('home/for-admins', name: 'app_for_admins')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');


        return $this->render('for_admins/index.html.twig', [
            'controller_name' => 'ForAdminsController',
        ]);
    }
}
