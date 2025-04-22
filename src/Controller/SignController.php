<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class SignController extends AbstractController
{
    #[Route('/sign_in', name: 'connexion')]
    public function signIn(Request $request): Response
    {
        return $this->render('sign/index.html.twig', [
            'page_name' => 'Connexion',
            'user' => $request->query->get('user')
        ]);
    }

    #[Route('/sign_up', name: 'inscription')]
    public function signUp(Request $request): Response
    {
        return $this->render('sign/index.html.twig', [
            'page_name' => 'Inscription',
            'user' => $request->query->get('user')
        ]);
    }
}
