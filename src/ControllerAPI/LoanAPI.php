<?php

namespace App\ControllerAPI;

use App\Repository\LoanRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class LoanAPI extends AbstractController
{
    #[Route('/api/loan', name: 'api_loan_add', methods: ['POST'])]
    public function addLoan(Request $request, LoanRepository $loan): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $idUser = $data['id_user'];
        $idMedia = $data['id_media'];

        if (!$idUser || !$idMedia) {
            return $this->json(['error' => 'Invalid fields'], 400);
        }

        $success = $loan->add($idUser, $idMedia);

        if ($success) {
            return $this->json(['message' => 'Media borrowed successfully']);
        }

        return $this->json(['error' => 'Error borrowing media'], 500);
    }
}