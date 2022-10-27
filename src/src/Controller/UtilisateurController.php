<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use App\Security\LoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class UtilisateurController extends AbstractController
{
    /**
     * @param UtilisateurRepository $utilisateurRepository
     * @return Response
     */
    #[Route('/users',name:'app_users_index')]
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        $utilisateurs = $utilisateurRepository->findAll();

        return $this->render('utilisateurs/index.html.twig',
            [
                "utilisateurs" => $utilisateurs
            ]);
    }

    /**
     * @throws ORMException
     */
    #[Route('/users/role-update/{id}', name:'app_users_update_role', methods: ['POST','GET'])]
    public function updateAdminRole($id,EntityManagerInterface $entityManager): Response
    {
        $utilisateur = $entityManager->getReference(Utilisateur::class,$id);
        $utilisateur->setIsAdmin(!$utilisateur->isIsAdmin());

        $entityManager->persist($utilisateur);
        $entityManager->flush();

        return $this->redirectToRoute("app_users_index");
    }

    /**
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws ORMException
     */
    #[Route('/users/delete/{id}', name:'app_users_delete', methods: ['DELETE','GET'])]
    public function deleteUser($id, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = $entityManager->getReference(Utilisateur::class,$id);

        $entityManager->remove($utilisateur);
        $entityManager->flush();

        return $this->redirectToRoute("app_users_index");
    }
}