<?php

namespace App\Controller;

use App\Repository\MediasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class SearchController extends AbstractController
{
    #[Route('recherche/', name: 'search')]
    public function index(SessionInterface $session, Request $request, MediasRepository $medias): Response {
        // Récupère l'utilisateur connecté avce la session (pas avec des cookies)
        $user = $session->get('user');
        $itemsPerPage = $request->query->get('itemsPerPage', 'tout');
        $page = (int) $request->query->get('page', 1);
        $query = null;
        $results = [];
        
        if ($request->isMethod('POST')) {
            $query = $request->request->get('query');
            //si le filtre de recherche est n'ets pas vide on renvoie ce qui correspond sinon
            //ce sont tous les reusltats qui restent
            if (!empty($query)) {
                $results = $medias->search($query);
            }   
        }
        
        //on recupere tous les medias si le filtre de recherche est vide
        if (empty($results)) {
            $results = $medias->findAll();
        }

        if ($itemsPerPage !== 'tout') {
            $itemsPerPage = (int) $itemsPerPage;
            $offset = ($page - 1) * $itemsPerPage;
            $paginatedResults = array_slice($results, $offset, $itemsPerPage);
        } else {
            $paginatedResults = $results; 
        }

        return $this->render('search/index.html.twig', [
            'page_name' => 'Recherche',
            'medias' => $paginatedResults,
            'user' => $user,
            'query' => $query,
            'itemsPerPage' => $itemsPerPage,
            'page' => $page,
            'totalResults' => count($results),
        ]);
    }
}
