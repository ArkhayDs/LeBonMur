<?php

namespace App\Controller;

use App\Service\DemoService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

// #[IsGranted('ROLE_XXX')] -> on peut faire un admin Controller avec une gestion d'accès globale sur la classe entière
// Il reste mieux de faire de la gestion d'accès directement dans les routes
class BaseController extends AbstractController
{
    #[Route('/login',name:'app_login')]
    public function login()
    {
        return $this->render("login.html.twig");
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
}