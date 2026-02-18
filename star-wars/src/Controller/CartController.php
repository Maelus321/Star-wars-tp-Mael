<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
        ]);
    }
    
    #[Route('/cart/add/{id}', name: 'cart_add', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function add(Product $product, EntityManagerInterface $em, Request $request): Response
    {
        // Vérification CSRF
        if (!$this->isCsrfTokenValid('add-to-cart-' . $product->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide');
            return $this->redirectToRoute('product_show', ['id' => $product->getId()]);
        }

        /** @var User $user */
        $user = $this->getUser();

        // Récupérer ou créer le panier de l'utilisateur
        $cart = $user->getCart();
        if (!$cart) {
            $cart = new Cart();
            $cart->setUser($user);
            $em->persist($cart);
        }

        // Vérifier si le produit est déjà dans le panier
        if ($cart->getProducts()->contains($product)) {
            $this->addFlash('warning', 'Ce produit est déjà dans votre panier');
        } else {
            $cart->addProduct($product);
            $this->addFlash('success', 'Produit ajouté au panier avec succès');
        }

        $em->flush();

        // Rediriger vers la page précédente ou la page du produit
        $referer = $request->headers->get('referer');
        return $referer ? $this->redirect($referer) : $this->redirectToRoute('product_show', ['id' => $product->getId()]);
    }

    #[Route('/cart/view', name: 'cart_view', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function view(EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $cart = $user->getCart();

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function remove(Product $product, EntityManagerInterface $em, Request $request): Response
    {
        // Vérification CSRF
        if (!$this->isCsrfTokenValid('remove-from-cart-' . $product->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide');
            return $this->redirectToRoute('cart_view');
        }

        /** @var User $user */
        $user = $this->getUser();
        $cart = $user->getCart();

        if (!$cart) {
            $this->addFlash('error', 'Panier vide');
            return $this->redirectToRoute('cart_view');
        }

        if (!$cart->getProducts()->contains($product)) {
            $this->addFlash('error', 'Produit non présent dans le panier');
            return $this->redirectToRoute('cart_view');
        }

        $cart->removeProduct($product);
        $em->flush();

        $this->addFlash('success', 'Produit retiré du panier');

        return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
    }
}