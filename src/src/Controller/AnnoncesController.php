<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
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
    public function showAnnonces(AnnonceRepository $annonceRepository) : Response
    {
        $annonces = $annonceRepository->findAllPublished();

        return $this->render('annonces/index.html.twig', [
            "annonces" => $annonces
        ]);
    }

    /**
     * @return Response
     */
    #[Route('/annonces/add', name:'app_annonce_add')]
    public function addAnnonce(): Response
    {
        return $this->render('annonces/addAnnonce.html.twig');
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/annonces/handle-form', name:'app_annonce_add_form', methods: ['POST'])]
    public function annonceFormHandler(Request $request, EntityManagerInterface $entityManager): Response
    {
        $newAnnonce = (new Annonce())
            ->setTitle($request->request->get("title"))
            ->setDescription($request->request->get("desc"))
            ->setPrice($request->request->get("price"))
            ->setAuthor()
            ->setPhotos(["pasdurl","toujourspas"])
            ->setCreatedAt(new \DateTime())
            ->setSlug(strtolower(str_replace(" ","-",$request->request->get("title"))));

        $entityManager->persist($newAnnonce);
        $entityManager->flush();

        return $this->redirectToRoute("app_index");
    }

/**
     * @return Response
     */
    #[Route('/annonces/{id}', name:'app_annonce_id', methods: ['GET'])]
    public function annonceById(AnnonceRepository $annonceRepository, $id): Response
    {
        $annonce = $annonceRepository->findByIdAndJoin($id);
        $tempo = [1,1,1];

        if (sizeof($annonce) === 1) {
            return $this->render('annonces/annonceById.html.twig', [
                'annonce' => $annonce[0],
                'tempo' => $tempo
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
}