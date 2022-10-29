<?php

namespace App\Controller;

use App\Entity\Reponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReponsesController extends AbstractController
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param QuestionRepository $questionRepository
     * @param $idAnnonce
     * @param $idQuestion
     * @return Response
     */
    #[Route('/reponse/add/{idAnnonce}&{idQuestion}', name:'app_reponse_add')]
    public function addAnnonce(Request $request, EntityManagerInterface $entityManager, QuestionRepository $questionRepository ,$idAnnonce, $idQuestion): Response
    {
        $utilisateur = $this->getUser();

        $newReponse = (new Reponse())
            ->setContent($request->request->get('contentReponse'))
            ->setAuthor($utilisateur)
            ->setQuestion($questionRepository->findOneBy(["id" => $idQuestion]))
            ->setSlug(strtolower(str_replace(" ","-",substr($request->request->get("contentReponse"),0,255))))
            ->setCreatedAt(new \DateTime());

        $entityManager->persist($newReponse);
        $entityManager->flush();

        return $this->redirectToRoute('app_annonce_id',[
            "id" => $idAnnonce
        ]);
    }
}