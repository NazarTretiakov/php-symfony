<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ThankForShoppingController extends AbstractController
{
    #[Route('home/thanks-for-shopping', name: 'app_thanks_for_shopping')]
    public function index(): Response
    {
        $isGranted = $this->isGranted('ROLE_ADMIN');

        return $this->render('thank_for_shopping/index.html.twig', [
            'controller_name' => 'ThankForShoppingController',
            'isGranted' => $isGranted
        ]);
    }
}
