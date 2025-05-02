<?php

namespace App\ControllerAPI;

use App\Repository\MediasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class SearchControllerAPI extends AbstractController
{
    #[Route('/api/search', name: 'api_search', methods: ['GET', 'POST'])]
    public function search(Request $request, MediasRepository $medias): JsonResponse
    {
        $itemsPerPage = $request->query->get('itemsPerPage', 'tout');
        $page = (int) $request->query->get('page', 1);
        $query = $request->query->get('query', null);
        $results = [];

        //utilisa la methode selon le champ
        if (!empty($query)) {
            $results = $medias->search($query);
        }

        //affiche tout si vide
        if (empty($results)) {
            $results = $medias->findAll();
        }

        // Pagination 
        if ($itemsPerPage !== 'tout') {
            $itemsPerPage = (int) $itemsPerPage;
            $offset = ($page - 1) * $itemsPerPage;
            $paginatedResults = array_slice($results, $offset, $itemsPerPage);
        } else {
            $paginatedResults = $results;
        }

        return $this->json([
            'page_name' => 'Recherche',
            'medias' => $paginatedResults,
            'query' => $query,
            'itemsPerPage' => $itemsPerPage,
            'page' => $page,
            'totalResults' => count($results),
        ]);
    }
}