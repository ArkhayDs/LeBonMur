<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Repository\AnnonceRepository;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnoncesController extends AbstractController
{
    /**
     * @param AnnonceRepository $annonceRepository
     * @param CategoriesRepository $categoriesRepository
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
     * @param CategoriesRepository $categoriesRepository
     * @return Response
     */
    #[Route('/annonces/add', name:'app_annonce_add')]
    public function addAnnonce(CategoriesRepository $categoriesRepository): Response
    {
        $utilisateur = $this->getUser();
        $categories = $categoriesRepository->findAll();

        return $this->render('annonces/addAnnonce.html.twig', [
            "utilisateur" => $utilisateur,
            "categories" => $categories
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param CategoriesRepository $categoriesRepository
     * @return Response
     */
    #[Route('/annonces/handle-form', name:'app_annonce_add_form', methods: ['POST'])]
    public function annonceFormHandler(Request $request, EntityManagerInterface $entityManager, CategoriesRepository $categoriesRepository): Response
    {
        $utilisateur = $this->getUser();
        $newAnnonce = (new Annonce())
            ->setTitle($request->request->get("title"))
            ->setDescription($request->request->get("description"))
            ->setPrice($request->request->get("price"))
            ->setAuthor($utilisateur)
            ->setCreatedAt(new \DateTime())
            ->setSlug(strtolower(str_replace(" ","-",$request->request->get("title"))));

        foreach($request->request->all()["categories"] as $category) {
            $newAnnonce->addCategory($categoriesRepository->findBy(["name" => $category])[0]);
        }

        $photos = [$request->files->get('file1'),$request->files->get('file2'),$request->files->get('file3')];
        $photosNames = [];
        foreach($photos as $photo) {
            if($photo) {
                $newPhoto = $photo;
                $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
                $originalPhotoName = $newPhoto->getClientOriginalName();

                $baseFileName = pathinfo($originalPhotoName, PATHINFO_FILENAME);
                $photoName = Urlizer::urlize($baseFileName) . '-' . uniqid() . '.' . $newPhoto->guessExtension();

                $newPhoto->move($destination, $photoName);
                $photosNames[] = $photoName;
            }
        }
        $newAnnonce->setPhotos($photosNames);

        $entityManager->persist($newAnnonce);
        $entityManager->flush();

        return $this->redirectToRoute("app_annonces");
    }

    /**
     * @param AnnonceRepository $annonceRepository
     * @param $id
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
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws ORMException
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
     * @param AnnonceRepository $annonceRepository
     * @param CategoriesRepository $categoriesRepository
     * @param $tag
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