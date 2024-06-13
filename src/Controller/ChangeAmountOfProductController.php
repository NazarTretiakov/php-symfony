<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ChangeAmountOfProductController extends AbstractController
{
    #[Route('home/for-admins/change-amount-of-product', name: 'app_change_amount_of_product')]
    public function index(ProductRepository $productRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $isGranted = $this->isGranted('ROLE_ADMIN');


        $products = $productRepository->findAll();



        return $this->render('change_amount_of_product/index.html.twig', [
            'controller_name' => 'ChangeAmountOfProductController',
            'isGranted' => $isGranted,
            'products' => $products
        ]);
    }
}
