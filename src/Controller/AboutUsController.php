<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AboutUsController extends AbstractController
{
    #[Route('home/about-us', name: 'app_about_us')]
    public function index(Request $request): Response
    {
        $isGranted = $this->isGranted('ROLE_ADMIN');

        if($request->getLocale() == 'en') {
            return $this->render('about_us/en/index.html.twig', [
                'controller_name' => 'AboutUsController',
                'isGranted' => $isGranted
            ]);
        } elseif($request->getLocale() == 'pl') {
            return $this->render('about_us/pl/index.html.twig', [
                'controller_name' => 'AboutUsController',
                'isGranted' => $isGranted
            ]);
        } else {
            return $this->render('about_us/jp/index.html.twig', [
                'controller_name' => 'AboutUsController',
                'isGranted' => $isGranted
            ]);
        }
    }
}
