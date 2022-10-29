<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    /**
     * @param UtilisateurRepository $utilisateurRepository
     * @return Response
     */
    #[Route('/users', name: 'app_users_index')]
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        $utilisateurs = $utilisateurRepository->findAll();

        return $this->render('utilisateurs/index.html.twig',
            [
                "utilisateurs" => $utilisateurs
            ]);
    }

    /**
     * @return Response
     */
    #[Route('/profil/{id}', name: 'app_user_id', methods: ['GET'])]
    public function userById(UtilisateurRepository $utilisateurRepository, $id): Response
    {
        $utilisateur = $this->getUser();
        $visitedUser = $utilisateurRepository->findByIdAndJoin($id);

        if (sizeof($visitedUser) === 1) {
            return $this->render('utilisateurs/profil.html.twig', [
                'visitedUser' => $visitedUser[0],
                'utilisateur' => $utilisateur

            ]);
        } else {
            return $this->redirectToRoute('app_index');
        }
    }

    /**
     * @throws ORMException
     */
    #[Route('/user/role-update/{id}', name: 'app_users_update_role', methods: ['POST', 'GET'])]
    public function updateAdminRole($id, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = $entityManager->getReference(Utilisateur::class, $id);
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
    #[Route('/user/delete/{id}', name: 'app_users_delete', methods: ['DELETE', 'GET'])]
    public function deleteUser($id, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = $entityManager->getReference(Utilisateur::class, $id);

        $entityManager->remove($utilisateur);
        $entityManager->flush();

        return $this->redirectToRoute("app_users_index");
    }

    /**
     * @param Utilisateur $utilisateur
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/user/note/{id}', name: 'app_user_note', methods: ['POST'])]
    public function userVote(Utilisateur $utilisateur, Request $request, EntityManagerInterface $entityManager): Response
    {
        $noter = $request->request->get('noter');

        if ($noter === 'up') {
            $utilisateur->upNote();
        }
        if ($noter === 'down') {
            $utilisateur->downNote();
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_user_id', [
            "id" => $utilisateur->getId()
        ]);
    }
}