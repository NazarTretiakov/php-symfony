<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Attribute\Route;
use function PHPUnit\Framework\isEmpty;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(ProductRepository $productRepository, Request $request, Session $session): Response
    {
        $isGranted = $this->isGranted('ROLE_ADMIN');


        $allProducts = $productRepository->findAll();
        $brandFilter = $request->get('brand');
        $sortingCondition = $request->get('sorting');
        $orderCondition = $request->get('order');

        if(!empty($brandFilter)) {
            if ($brandFilter == 'all') {
                $products = $productRepository->createQueryBuilder('p')->orderBy('p.' . $sortingCondition, $orderCondition)->getQuery()->getResult();
            } else {
                $products = $productRepository->createQueryBuilder('p')->where('p.brand = :brandFilter')->setParameter('brandFilter', $brandFilter)->orderBy('p.' . $sortingCondition, $orderCondition)->getQuery()->getResult();
            }
        } else {
            $products = $allProducts;
        }


        $brands = [];

        foreach ($allProducts as $product) {
            $brands[] = $product->getBrand();
        }
        $brands = array_unique($brands);

        $cart = $session->get('cart', []);
        $amountOfProductsInCart = count($cart);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'isGranted' => $isGranted,
            'brands' => $brands,
            'products' => $products,
            'selectedBrand' => $brandFilter,
            'selectedSortingCondition' => $sortingCondition,
            'selectedOrderCondition' => $orderCondition,
            'amountOfProductsInCart' => $amountOfProductsInCart
        ]);
    }
}
