<?php

namespace App\Security;

use App\Service\DemoService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class LoginAuthenticator extends AbstractAuthenticator implements AuthenticationEntryPointInterface
{
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {

        $this->urlGenerator = $urlGenerator;
    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === "app_login"
            && $request-> isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        return new Passport(
            new UserBadge($request->request->get('name')),
            new PasswordCredentials($request->request->get('password')),
            [
                new CsrfTokenBadge("login_form",$request->request->get('csrf'))
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->urlGenerator->generate('app_index'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new RedirectResponse($this->urlGenerator->generate('app_login'),
            Response::HTTP_UNAUTHORIZED
        );
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
