<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController{

    #[Route('/', name: 'home', methods: ['GET', 'POST'])]
    function index(Request $request): Response {
        //on initialise user sinon on a une erreur
        $user= null;

        if ($request->isMethod('POST')) {
            $firstname = $request->request->get('firstname');
            $lastname = $request->request->get('lastname');
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            //on stock dans user les infos et on les render 
            $user = [
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'password' => $password
            ];
        }

        return $this->render('home/index.html.twig', [
            //render la page avec le titre et les infos de l'utilisateur
            'page_name' => 'Accueil',
            'user' => $user
        ]);
    }
}