<?php

namespace App\Service;

use http\Client\Curl\User;
use Symfony\Component\Security\Core\Security;

class DemoService
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function demoMethod(User $user)
    {
        dump($this->security->getUser());
    }
}