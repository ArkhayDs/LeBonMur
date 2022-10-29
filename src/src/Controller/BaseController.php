<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Security\LoginAuthenticator;
use App\Service\DemoService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

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
        $utilisateur = $this->getUser();

        return $this->render('home/index.html.twig', [
            "utilisateur" => $utilisateur
        ]);
    }

    #[Route('/login',name:'app_login')]
    public function login()
    {
        return $this->render("utilisateurs/login.html.twig");
    }

    #[Route('/register',name:'app_register')]
    public function register(Request                        $request,
                             EntityManagerInterface         $entityManager,
                             UserPasswordHasherInterface    $hasher,
                             UserAuthenticatorInterface     $authenticator,
                             LoginAuthenticator             $loginAuthenticator): Response
    {
        if ($request->isMethod('POST')) {
            if (!empty($request->request->get('password'))
                && !empty($request->request->get('password2'))
                && $request->request->get('password') === $request->request->get('password2')
                && $this->isCsrfTokenValid('register_form', $request->request->get('csrf'))) {

                $utilisateur = new Utilisateur();
                $utilisateur->setName($request->request->get('name'))
                    ->setEmail($request->request->get('email'))
                    ->setPassword($hasher->hashPassword($utilisateur, $request->request->get('password')));

                $entityManager->persist($utilisateur);
                $entityManager->flush();

                $this->addFlash('success', 'Création de compte réussie ! Bienvenue '.$utilisateur->getName().' !');

                return $authenticator->authenticateUser(
                    $utilisateur,
                    $loginAuthenticator,
                    $request
                );
            }

            $this->addFlash('error', 'La création à échouée ! Les deux mots de passes ne correspondent pas.');

            return $this->render('utilisateurs/register.html.twig');
        }

        return $this->render('utilisateurs/register.html.twig');
    }

    #[Route('/logout',name:'app_logout')]
    public function logout()
    {
        //
    }

    #[Route('/admin',name:'app_admin')]
    #[IsGranted('ROLE_ADMIN')] // valable, tout comme la gestion depuis access_control de security.yaml. A la préférence du dev pour l'organisation du code.
    public function adminPage()
    {
        $utilisateur = $this->getUser();

        return $this->render('utilisateurs/admin.html.twig', [
            "utilisateur" => $utilisateur
        ]);
    }

    #[Route('/surprise/pour/jf',name:'app_surprise_jf')]
    public function jfRedirect()
    {
        return $this->redirect("https://www.triumphmotorcycles.fr/configure/bike/b80c9f60-7dc7-4b21-b00c-d008cbbe913e/configure#config");
    }
}