<?php

namespace App\Controller;

use App\Service\DemoService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// #[IsGranted('ROLE_XXX')] -> on peut faire un admin Controller avec une gestion d'accès globale sur la classe entière
// Il reste mieux de faire de la gestion d'accès directement dans les routes
class BaseController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/',name:'app_index')]
    public function index() : Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/login',name:'app_login')]
    public function login()
    {
        return $this->render("utilisateurs/login.html.twig");
    }

    #[Route('/logout',name:'app_logout')]
    public function logout()
    {
//
    }

    #[Route('/admin',name:'app_admin')]
//    #[IsGranted('ROLE_ADMIN')] // valable, tout comme la gestion depuis access_control de security.yaml. A la préférence du dev pour l'organisation du code.
    public function adminPage(DemoService $demoService)
    {
//        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // valable, mais on a encore mieux !
        $utilisateur = $this->getUser();

        $demoService->demoMethod($utilisateur);

        return $this->render('admin.html.twig', [
            "utilisateur" => $utilisateur
        ]);
    }

    #[Route('/pub/surprise/pour/jf',name:'app_pub')]
    public function pubRedirect()
    {
        return $this->redirect("https://www.triumphmotorcycles.fr/configure/bike/b80c9f60-7dc7-4b21-b00c-d008cbbe913e/configure#config");
    }
}