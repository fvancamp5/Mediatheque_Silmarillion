<?php

namespace App\ControllerAPI;

use App\Repository\LoanRepository;
use App\Repository\MediasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

final class MediasController extends AbstractController {

    #[Route('/api/medias/{id}', name: 'api_media_get', methods: ['GET'])]
    public function indexMedia(int $id, MediasRepository $medias): JsonResponse {
        $media = $medias->find($id);
        if (!$media) {
            return new JsonResponse(['error' => 'Erreur lors de la recherche du média'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($media);
    }

    #[Route('/api/medias/{id}', name: 'api_media_update', methods: ['PUT'])]
    public function modificationMedia(int $id, Request $request, MediasRepository $medias): JsonResponse {
        $media = $medias->find($id);
        if (!$media) {
            return new JsonResponse(['error' => 'Erreur lors de la recherche du média'], Response::HTTP_NOT_FOUND);
        }

        $title = $request->request->get('title');
        $author = $request->request->get('author');
        $type = $request->request->get('type');
        $description = $request->request->get('description');
        $image = $request->request->get('image');

        //si le form est soumis, on modifie le media
        if ($medias->update($id, $title, $author, $type, $description, $image)) {
            return new JsonResponse(['message' => 'Média modifié'], Response::HTTP_OK);
        }

        return new JsonResponse(['error' => 'Erreur lors de la modification'], Response::HTTP_BAD_REQUEST);
    }

    #[Route('/api/medias/{id}', name: 'api_media_delete', methods: ['DELETE'])]
    public function deleteMedia(int $id, MediasRepository $medias): JsonResponse {
        $media = $medias->find($id);
        if (!$media) {
            return new JsonResponse(['error' => 'Pas de média correspondat'], Response::HTTP_NOT_FOUND);
        }

        $medias->delete($id);
        return new JsonResponse(['message' => 'Média supprimé'], Response::HTTP_OK);
    }

    #[Route('/api/medias', name: 'api_media_add', methods: ['POST'])]
    public function addMedia(Request $request, MediasRepository $medias): JsonResponse {
        $title = $request->request->get('title');
        $author = $request->request->get('author');
        $type = $request->request->get('type');
        $description = $request->request->get('description');
        $image = $request->request->get('image');

        if ($medias->add($title, $author, $type, $description, $image)) {
            return new JsonResponse(['message' => 'Média ajouté'], Response::HTTP_CREATED);
        }

        return new JsonResponse(['error' => "Erreur lord de l'ajout"], Response::HTTP_BAD_REQUEST);
    }
}