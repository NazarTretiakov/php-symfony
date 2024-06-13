<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\AddNewProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AddNewProductController extends AbstractController
{
    #[Route('home/for-admins/add-new-product.css', name: 'app_add_new_product')]
    public function index(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, string $imageDirectory = 'uploads/img'): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $isGranted = $this->isGranted('ROLE_ADMIN');

        $product = new Product();
        $form = $this->createForm(AddNewProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();

            if($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                $file->move($imageDirectory, $newFilename);

                $product->setImagePath($newFilename);
            }
            $product = $form->getData();

            $entityManager->persist($product);

            $entityManager->flush();

            $this->addFlash('notice', 'The product has been added');

            return $this->redirectToRoute('app_for_admins');
        }
        return $this->render('add_new_product/index.html.twig', [
            'form' => $form,
            'isGranted' => $isGranted
        ]);
    }
}
