<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;



class ProductController extends AbstractController
{
    //create
    #[Route('/forms/new_Product', name: 'new_product')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response 
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($product);
            $entityManager->flush();
            $this->addFlash('success', 'Product created with success');
            return $this->redirectToRoute('product_list');
        }

        return $this->render('forms/new_Product.html.twig', [
            "form" => $form->createView()
        ]);
    }

//delete
#[Route('/product/{id}/delete', name: 'product_delete')]
public function delete(Product $product, EntityManagerInterface $entityManager): Response
{
    // Supprime le produit
    $entityManager->remove($product);
    $entityManager->flush();
    
    // Message de confirmation
    $this->addFlash('success', 'Product deleted with success');
    
    // Redirige vers la liste
    return $this->redirectToRoute('product_list');
}


//read
#[Route('/products', name: 'product_list')]
public function list(ProductRepository $productRepository): Response
{
    $products = $productRepository->findAll();
    
    return $this->render('product/list.html.twig', [
        'products' => $products
    ]);
}

//update
#[Route('/product/{id}/edit', name: 'product_edit')]
public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
{
    // Crée le formulaire à partir du produit existant
    $form = $this->createForm(ProductType::class, $product);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Pas besoin de persist, l'entité est déjà suivie par Doctrine
        $entityManager->flush();
        
        $this->addFlash('success', 'Product updated with success');
        
        return $this->redirectToRoute('product_list');
    }

    return $this->render('forms/edit_Product.html.twig', [
        'form' => $form->createView(),
        'product' => $product
    ]);
}
#[Route('/product/{id}', name: 'product_show', requirements: ['id' => '\d+'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

}

