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

        $media1 = [
            'title' => 'Dark Souls',
            'author' => 'From Software',
            'type' => 'Jeu vidéo',
            'image' => 'ds_1.png',
            'state' => true
        ];
        $media2 = [
            'title' => 'Dark Souls 3',
            'author' => 'From Software',
            'type' => 'Jeu vidéo',
            'image' => 'ds_3.png',
            'state' => true
        ];
        $media3 = [
            'title' => 'Shadow of the Colossus',
            'author' => 'Team Ico',
            'type' => 'Jeu vidéo',
            'image' => 'sotc.png',
            'state' => true
        ];
        $media4 = [
            'title' => 'Shining',
            'author' => 'Stephen King',
            'type' => 'Livre',
            'image' => 'shining.png',
            'state' => true
        ];
        $medias = [
            $media1,
            $media2,
            $media3,
            $media4
        ];


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
            'user' => $user,
            'medias' => $medias
        ]);
    }
}