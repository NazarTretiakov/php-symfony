<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ChooseSizeController extends AbstractController
{
    #[Route('home/choose-size', name: 'app_choose_size')]
    public function index(ProductRepository $productRepository, Request $request): Response
    {
        $isGranted = $this->isGranted('ROLE_ADMIN');

        $id = $request->get('id');
        $product = $productRepository->find($id);

        $chosenSize = $request->get('chosen-size');

        if(!empty($chosenSize)) {
            return $this->redirectToRoute('app_add_to_cart', ['id' => $id]);
        }

        return $this->render('choose_size/index.html.twig', [
            'controller_name' => 'ChooseSizeController',
            'isGranted' => $isGranted,
            'product' => $product
        ]);
    }
}
