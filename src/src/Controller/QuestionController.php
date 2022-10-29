<?php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param AnnonceRepository $annonceRepository
     * @param $idAnnonce
     * @return Response
     */
    #[Route('/question/add/{idAnnonce}', name:'app_question_add')]
    public function addAnnonce(Request $request, EntityManagerInterface $entityManager, AnnonceRepository $annonceRepository ,$idAnnonce): Response
    {
        $utilisateur = $this->getUser();

        $newQuestion = (new Question())
            ->setContent($request->request->get('content'))
            ->setAuthor($utilisateur)
            ->setAnnonce($annonceRepository->findOneBy(["id" => $idAnnonce]))
            ->setSlug(strtolower(str_replace(" ","-",substr($request->request->get("content"),0,255))))
            ->setCreatedAt(new \DateTime());

        $entityManager->persist($newQuestion);
        $entityManager->flush();

        return $this->redirectToRoute('app_annonce_id',[
            "id" => $idAnnonce
        ]);
    }
}