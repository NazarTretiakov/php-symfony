<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Attribute\Route;

class ProductDetailsController extends AbstractController
{
    #[Route('home/product-details', name: 'app_product_details')]
    public function index(Request $request, ProductRepository $productRepository, Session $session): Response
    {
        $isGranted = $this->isGranted('ROLE_ADMIN');


        $id = $request->get('id');

        $product = $productRepository->find($id);

        $cart = $session->get('cart', []);
        $amountOfProductsInCart = count($cart);


        $chosenSize = $request->get('chosen-size');

        if(!empty($chosenSize)) {
            return $this->redirectToRoute('app_add_to_cart', ['id' => $id]);
        }

        return $this->render('product_details/index.html.twig', [
            'controller_name' => 'ProductDetailsController',
            'isGranted' => $isGranted,
            'product' => $product,
            'amountOfProductsInCart' => $amountOfProductsInCart
        ]);
    }
}
