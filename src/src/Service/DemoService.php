<?php

namespace App\Service;

use App\Entity\Utilisateur;
use Symfony\Component\Security\Core\Security;

class DemoService
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function demoMethod(Utilisateur $utilisateur)
    {
        dump($this->security->getUser());
    }
}