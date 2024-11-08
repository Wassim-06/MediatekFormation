<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Ce contrôleur gère les opérations liées aux connexion de la partie admin
 *
 * @author Wa2s
 */
class LoginController extends AbstractController
{

    /**
     * Affiche la page pour se connecter avec un compte Admin
     * @Route (path: '/login', name: 'app_login')
     * @param AuthenticationUtils $authenticationUtils Service pour gérer l'authentification.
     * @return Response La réponse HTTP avec la page d'authentification
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Récupère l'erreur de connexion si il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();

        // Dernier nom d'utilisateur saisie par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * Fonction qui permet de se déconnecter
     * @Route (path: '/logout', name: 'app_logout')
     * @return void
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
