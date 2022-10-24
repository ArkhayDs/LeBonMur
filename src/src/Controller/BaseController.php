<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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

    }
}