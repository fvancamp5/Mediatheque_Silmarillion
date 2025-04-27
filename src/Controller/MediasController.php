<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class MediasController extends AbstractController{


#[Route('medias/{id}', name: 'home_id', methods: ['GET'])]
    function indexMedia(int $id): Response {
        $user= null;

        //medias en static pour tester
        $media1 = [
            'id' => 1,
            'title' => 'Dark Souls',
            'author' => 'From Software',
            'type' => 'Jeu vidéo',
            'image' => 'ds_1.png',
            'description' => 'Dark Souls est un jeu vidéo d\'action-RPG développé par From Software et publié par Bandai Namco Entertainment. ',
            'state' => true
        ];
        $media2 = [
            'id' => 2,
            'title' => 'Dark Souls 3',
            'author' => 'From Software',
            'type' => 'Jeu vidéo',
            'image' => 'ds_3.png',
            'description' => 'Dark Souls 3 est un jeu de rôle et d\'action développé par From Software et publié par Bandai Namco Entertainment. ',
            'state' => false
        ];
        $media3 = [
            'id' => 3,
            'title' => 'Shadow of the Colossus',
            'author' => 'Team Ico',
            'type' => 'Jeu vidéo',
            'image' => 'sotc.png',
            'description' => 'Shadow of the Colossus est un jeu vidéo d\'action-aventure développé par Team Ico et publié par Sony Computer Entertainment. ',
            'state' => true
        ];
        $media4 = [
            'id' => 4,
            'title' => 'Shining',
            'author' => 'Stephen King',
            'type' => 'Livre',
            'image' => 'shining.png',
            'description' => 'Shining est un roman d\'horreur racontant l\'histoire de Jack Torrance, un écrivain en difficulté qui accepte un emploi de gardien d\'hiver dans un hôtel isolé dans les montagnes du Colorado.',
            'state' => true
        ];
        $medias = [
            $media1,
            $media2,
            $media3,
            $media4,
        ];

        foreach ($medias as $elem) {
            if ($elem['id'] == $id) {
                $media = $elem;
                break;
            }
        }
    
        if (!$media) {
            throw $this->createNotFoundException('Media not found');
        }
    
        return $this->render('medias/index.html.twig', [
            'page_name' => 'Accueil',
            'user' => $user,
            'media' => $media
        ]);
    }
}