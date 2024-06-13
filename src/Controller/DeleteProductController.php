<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeleteProductController extends AbstractController
{
    #[Route('home/for-admins/delete-product', name: 'app_delete_product')]
    public function index(ProductRepository $productRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $isGranted = $this->isGranted('ROLE_ADMIN');

        $products = $productRepository->findAll();


        $productId = $request->get('id');
        if(!empty($productId)) {
            $productToDelete = $productRepository->find($productId);
            $entityManager->remove($productToDelete);

            $this->addFlash('notice', 'The product has been deleted');

            $entityManager->flush();
            return $this->redirectToRoute('app_for_admins');
        }
        return $this->render('delete_product/index.html.twig', [
            'controller_name' => 'DeleteProductController',
            'isGranted' => $isGranted,
            'products' => $products
        ]);
    }
}
