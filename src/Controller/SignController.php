<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class SignController extends AbstractController
{
    #[Route('/sign_in', name: 'connexion')]
    public function signIn(SessionInterface $session): Response
    {
        $connexion = true;
        $error = null;

        $session->set('connexion', $connexion);

        return $this->render('sign/index.html.twig', [
            'page_name' => 'Connexion',
            'connexion' => $connexion,
            'error' => $error,
        ]);
    }

    #[Route('/sign_up', name: 'inscription')]
    public function signUp(SessionInterface $session): Response
    {
        $connexion = false;
        $error = null;

        $session->set('connexion', $connexion);


        return $this->render('sign/index.html.twig', [
            'page_name' => 'Inscription',
            'connexion' => $connexion,
            'error' => $error,
        ]);
    }
}
