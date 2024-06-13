<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ChangingAmountController extends AbstractController
{
    #[Route('home/for-admins/change-amount-of-product/changing-amount', name: 'app_changing_amount')]
    public function index(ProductRepository $productRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $isGranted = $this->isGranted('ROLE_ADMIN');

        $productId = $request->get('id');
        if(!empty($productId)) {
            $product = $productRepository->find($productId);
        }

        $newAmountOfProduct = $request->get('new-amount');
        if(!empty($newAmountOfProduct)) {
            $product = $productRepository->find($productId);

            $product->setAmount("$newAmountOfProduct");
            $entityManager->flush();

            $this->addFlash('notice', 'The amount of product has been changed');

            $products = $productRepository->findAll();

            return $this->redirectToRoute('app_for_admins');
        }



        return $this->render('changing_amount/index.html.twig', [
            'controller_name' => 'ChangingAmountController',
            'isGranted' => $isGranted,
            'product' => $product
        ]);
    }
}
