<?php

namespace App\DataFixtures;

use App\Factory\AnnonceFactory;
use App\Factory\CategoriesFactory;
use App\Factory\QuestionFactory;
use App\Factory\ReponseFactory;
use App\Factory\UtilisateurFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UtilisateurFactory::createMany(10);
        CategoriesFactory::createMany(5);
        AnnonceFactory::createMany(50);
        QuestionFactory::createMany(100);
        ReponseFactory::createMany(100);
    }
}
