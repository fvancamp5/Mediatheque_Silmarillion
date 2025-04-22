<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class SearchController extends AbstractController
{
    #[Route('/recherche', name: 'search')]
    public function index(Request $request): Response {
        return $this->render('search/index.html.twig', [
            'page_name' => 'Recherche',
            'user' => $request->query->get('user')
        ]);
    }
}
