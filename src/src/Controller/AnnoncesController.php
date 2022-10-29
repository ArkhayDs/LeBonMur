<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Repository\AnnonceRepository;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use PhpParser\Node\Expr\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnoncesController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/annonces',name:'app_annonces')]
    public function showAnnonces(AnnonceRepository $annonceRepository, CategoriesRepository $categoriesRepository) : Response
    {
        $utilisateur = $this->getUser();
        $annonces = $annonceRepository->findAllAndJoin();
        $categories = $categoriesRepository->findAll();

        return $this->render('annonces/index.html.twig', [
            "annonces" => $annonces,
            "utilisateur" => $utilisateur,
            "categories" => $categories
        ]);
    }

    /**
     * @return Response
     */
    #[Route('/annonces/add', name:'app_annonce_add')]
    public function addAnnonce(): Response
    {
        $utilisateur = $this->getUser();
        return $this->render('annonces/addAnnonce.html.twig', [
            "utilisateur" => $utilisateur
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/annonces/handle-form', name:'app_annonce_add_form', methods: ['POST'])]
    public function annonceFormHandler(Request $request, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = $this->getUser();
        $newAnnonce = (new Annonce())
            ->setTitle($request->request->get("title"))
            ->setDescription($request->request->get("description"))
            ->setPrice($request->request->get("price"))
            ->setAuthor($utilisateur)
            ->setPhotos(["pasdurl","toujourspas"])
            ->setCreatedAt(new \DateTime())
            ->setSlug(strtolower(str_replace(" ","-",$request->request->get("title"))));

        $entityManager->persist($newAnnonce);
        $entityManager->flush();

        return $this->redirectToRoute("app_annonces");
    }

    /**
     * @return Response
     */
    #[Route('/annonces/{id}', name:'app_annonce_id', methods: ['GET'])]
    public function annonceById(AnnonceRepository $annonceRepository, $id): Response
    {
        $utilisateur = $this->getUser();
        $annonce = $annonceRepository->findByIdAndJoin($id);
        $tempo = [1,1,1];

        if (sizeof($annonce) === 1) {
            return $this->render('annonces/annonceById.html.twig', [
                'annonce' => $annonce[0],
                'tempo' => $tempo,
                'utilisateur' => $utilisateur
            ]);
        } else {
            return $this->redirectToRoute('app_index');
        }
    }



    /**
     * @throws ORMException
     * @return Response
     */
    #[Route('/annonces/delete/{id}', name:'app_annonce_delete', methods: ['DELETE'])]
    public function deleteAnnonce($id, EntityManagerInterface $entityManager): Response
    {
        $annonce = $entityManager->getReference(Annonce::class, $id);

        $entityManager->remove($annonce);
        $entityManager->flush();

        return $this->redirectToRoute("app_index");
    }

    /**
     * @return Response
     */
    #[Route('/annonces/categorie/{tag}',name:'app_annonce_from_tag')]
    public function showAllFromTag(AnnonceRepository $annonceRepository, CategoriesRepository $categoriesRepository, $tag) : Response
    {
        $utilisateur = $this->getUser();
        $annonces = $annonceRepository->findAllFromTag($tag);
        $categories = $categoriesRepository->findAll();

        return $this->render('annonces/fromTag.html.twig', [
            "annonces" => $annonces,
            "utilisateur" => $utilisateur,
            "categories" => $categories
        ]);
    }
}