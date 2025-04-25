<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{
    #[Route('compte/', name: 'account', methods: ['GET'])]
    function index(): Response {
        //on initialise user sinon on a une erreur
        $user= null;

        return $this->render('account/index.html.twig', [
            'page_name' => 'Mon compte',
            'user' => $user
        ]);
    }

    #[Route('emprunts/', name: 'history', methods: ['GET'])]
    function borrows(): Response {
        //on initialise user sinon on a une erreur
        $user= null;

        return $this->render('account/history.html.twig', [
            'page_name' => 'Mes emprunts',
            'user' => $user
        ]);
    }
}
