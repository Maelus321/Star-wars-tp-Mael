<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'user_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Vérification de connexion
        if (!$this->getUser()) {
            $this->addFlash('error', 'Vous devez être connecté pour voir la liste des utilisateurs.');
            return $this->redirectToRoute('product_list');
        }
        
        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/new', name: 'user_new')]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        
        
        $user = new User();

        $form = $this->createForm(UserType::class, $user, [
            'is_edit' => false,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion du mot de passe
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }

            // Gestion des rôles : transformer la valeur unique en tableau
            $role = $form->get('roles')->getData();
            $user->setRoles([$role]);

            // Sauvegarde de l'utilisateur
            $entityManager->persist($user);
            $entityManager->flush();

            // Création automatique du panier associé
            $cart = new Cart();
            $cart->setUser($user);
            $entityManager->persist($cart);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur créé avec succès.');

            return $this->redirectToRoute('product_list');
        }

        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'user_show', requirements: ['id' => '\d+'])]
    public function show(User $user): Response
    {
        // Vérification de connexion
        if (!$this->getUser()) {
            $this->addFlash('error', 'Vous devez être connecté pour voir les détails d\'un utilisateur.');
            return $this->redirectToRoute('product_list');
        }
        
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'user_edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Vérification de connexion
        if (!$this->getUser()) {
            $this->addFlash('error', 'Vous devez être connecté pour modifier un utilisateur.');
            return $this->redirectToRoute('product_list');
        }
        
        // Pour pré-remplir le champ roles, on récupère le premier rôle (ou ROLE_USER par défaut)
        $form = $this->createForm(UserType::class, $user, [
            'is_edit' => true,
        ]);

        // On initialise la valeur du champ roles à partir de l'entité
        $currentRoles = $user->getRoles();
        $form->get('roles')->setData($currentRoles[0] ?? 'ROLE_USER');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Mot de passe : seulement s'il est renseigné
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }

            // Gestion des rôles : transformer la valeur unique en tableau
            $role = $form->get('roles')->getData();
            $user->setRoles([$role]);

            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur modifié avec succès.');

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/{id}/delete', name: 'user_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // Vérification de connexion
        if (!$this->getUser()) {
            $this->addFlash('error', 'Vous devez être connecté pour supprimer un utilisateur.');
            return $this->redirectToRoute('product_list');
        }
        
        // La vérification CSRF est recommandée
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'Utilisateur supprimé.');
        }

        return $this->redirectToRoute('user_index');
    }
}