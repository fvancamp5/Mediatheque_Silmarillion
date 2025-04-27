<?php

namespace App\Controller;

use App\Repository\MediasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class MediasController extends AbstractController{


#[Route('medias/{id}', name: 'home_id', methods: ['GET'])]
    function indexMedia(int $id, MediasRepository $medias): Response {
        $user= null;

        $media = $medias->find($id);
    
        return $this->render('medias/index.html.twig', [
            'page_name' => 'Accueil',
            'user' => $user,
            'media' => $media
        ]);
    }
}