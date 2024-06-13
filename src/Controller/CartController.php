<?php

namespace App\Controller;

use App\Entity\OrderProduct;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Order;

class CartController extends AbstractController
{
    #[Route('/home/cart', name: 'app_cart')]
    public function index(ProductRepository $productRepository, Request $request, Session $session, EntityManagerInterface $entityManager): Response
    {
        $isGranted = $this->isGranted('ROLE_ADMIN');


        $cart = $session->get('cart', []);

        $totalPrice = 0;
        foreach($cart as $product) {
            $totalPrice += $product->getPrice();
        }

        $addressOfDelivery = $request->get('address_of_delivery');
        if(!empty($addressOfDelivery)) {

            foreach($cart as $product) {
                $productFromDB = $productRepository->find($product->getId());
                if(empty($productFromDB)) {

                    $cart = [];
                    $session->set('cart', $cart);
                    $this->addFlash('error', "Some product in your cart is no longer exists");
                    return $this->redirectToRoute('app_home');
                }
            }

            foreach($cart as $product) {
                $productFromDB = $productRepository->find($product->getId());

                $amountOfProductInCart = 0;
                foreach($cart as $tempProduct) {
                    if($tempProduct->getId() == $product->getId()) {
                        $amountOfProductInCart += 1;
                    }
                }
                if($productFromDB->getAmount() < $amountOfProductInCart) {
                    $nameOfProduct = $productFromDB->getName();
                    $this->addFlash('error', "The amount of \"{$nameOfProduct}\" is too large in our cart. There is no that much {$nameOfProduct} in store");
                    return $this->redirectToRoute('app_home');
                }
            }

            if(empty($this->getUser())) {
                $this->addFlash('error', 'You have to be logged into account to buy products');
                return $this->redirectToRoute('app_home');
            }

            if(empty($cart)) {
                $this->addFlash('error', 'There are no products in your cart');
                return $this->redirectToRoute('app_home');
            }

            $order = new Order();
            $order->setUser($this->getUser());
            $order->setOrderDate(new \DateTime());
            $order->setAddressOfDelivery($addressOfDelivery);
            $entityManager->persist($order);
            $entityManager->flush();

            foreach($cart as $product) {
//                $productId = $product->getId();
//                $productFromDB = $productRepository->find($productId);

//                $orderProduct = new OrderProduct();
//                $orderProduct->setProduct($productFromDB);
//                $orderProduct->setOrder($order);
//                $entityManager->persist($orderProduct);
//                $entityManager->flush();



//                $order->addProduct($product);
//                $product->addOrder($order);
//                $entityManager->persist($product);
//                $entityManager->flush();
            }



            foreach($cart as $product) {
                $productToChangeAmount = $productRepository->find($product->getId());
                $productToChangeAmount->setAmount(($productToChangeAmount->getAmount() - 1));
                $entityManager->persist($productToChangeAmount);
            }

            $entityManager->persist($order);
            $entityManager->flush();

            $cart = [];
            $session->set('cart', $cart);
            return $this->redirectToRoute('app_thanks_for_shopping');
        }




        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'isGranted' => $isGranted,
            'cart' => $cart,
            'totalPrice' => $totalPrice
        ]);
    }
    #[Route('/home/add-to-cart', name: 'app_add_to_cart')]
    public function addProductToCart(Session $session, Request $request, ProductRepository $productRepository): Response
    {
        $productId = $request->get('id');

        if(empty($productId)) {
            $this->addFlash('error', 'Error, you are trying to add product to cart, but you don\'t specify the product id');
            return $this->redirectToRoute('app_home');
        }

        $cart = $session->get('cart', []);

        $productToAdd = $productRepository->find($productId);

        if(empty($productToAdd)) {
            $this->addFlash('error', 'Error, the product that you are trying to add to cart does not exists');
            return $this->redirectToRoute('app_home');
        }

        $cart[] = $productToAdd;
        $session->set('cart', $cart);

        return $this->redirectToRoute('app_home');
    }
    #[Route('/home/remove-from-cart', name: 'app_remove_from_cart')]
    public function removeFromCart(Session $session, Request $request, ProductRepository $productRepository): Response
    {
        $productId = $request->get('id');

        if(empty($productId)) {
            $this->addFlash('error', 'Error, you are trying to remove product from cart, but you don\'t specify the product id');
            return $this->redirectToRoute('app_home');
        }

        $cart = $session->get('cart', []);

        $productToRemove = $productRepository->find($productId);

        if(empty($productToRemove)) {
            $this->addFlash('error', 'Error, the product that you are trying to remove from cart does not exists');
            return $this->redirectToRoute('app_home');
        }

        if ($productToRemove) {
            foreach ($cart as $key => $product) {

                if ($product->getId() == $productId) {
                    unset($cart[$key]);
                    break;
                }
            }
        }

        $session->set('cart', $cart);


        return $this->redirectToRoute('app_cart');
    }
}
