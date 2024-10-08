<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        // Si c'est une requête AJAX (exemple Vue.js)
        if ($request->isXmlHttpRequest()) {
            $error = $authenticationUtils->getLastAuthenticationError();

            if ($error) {
                return new JsonResponse(['error' => 'Identifiants incorrects'], Response::HTTP_UNAUTHORIZED);
            }

            if ($this->isGranted('ROLE_ADMIN')) {
                return new JsonResponse(['role' => 'ROLE_ADMIN']);
            }

            if ($this->isGranted('ROLE_USER')) {
                return new JsonResponse(['role' => 'ROLE_USER']);
            }
        }

        // Si c'est une requête classique
        return $this->render('security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('Cette méthode peut rester vide, elle sera interceptée par la clé de sécurité du firewall.');
    }
}
