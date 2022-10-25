<?php

namespace App\Controller;

use App\Service\DemoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

        return $this->render('utilisateurs/admin.html.twig', [
            "utilisateur" => $utilisateur
        ]);
    }
}