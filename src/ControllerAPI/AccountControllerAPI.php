<?php

namespace App\ControllerAPI;

use App\Repository\HistoryRepository;
use App\Repository\LoanRepository;
use App\Repository\MediasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

final class AccountControllerAPI extends AbstractController
{
    #[Route('/api/account', name: 'api_account', methods: ['GET'])]
    public function getAccount(SessionInterface $session, MediasRepository $medias, HistoryRepository $history): JsonResponse
    {
        $user = $session->get('user');

        if ($user === null) {
            return $this->json(['error' => 'Pas connecté'], 401);
        }

        $mediasArray = [];
        $myHistory = $history->findBy(['id_user' => $user['id']]);

        foreach ($myHistory as $loan) {
            $media = $medias->findOneBy(['id' => $loan->getIdMedia()]);
            if ($media) {
                $mediasArray[] = [
                    'id' => $media->getId(),
                    'title' => $media->getTitle(),
                    'author' => $media->getAuthor(),
                    'type' => $media->getType(),
                    'description' => $media->getDescription(),
                    'image' => $media->getImage(),
                    'status' => $media->isStatus(),
                ];
            }
        }

        return $this->json([
            'user' => $user,
            'medias' => $mediasArray,
        ]);
    }

    #[Route('/api/loans', name: 'api_loans', methods: ['GET'])]
    public function getLoans(SessionInterface $session, MediasRepository $medias, LoanRepository $loan, HistoryRepository $history): JsonResponse
    {
        $user = $session->get('user');

        if ($user === null) {
            return $this->json(['error' => 'User not logged in'], 401);
        }

        $mediasArray = [];
        $myLoans = $loan->findBy(['idUser' => $user['id']]);

        foreach ($myLoans as $loan) {
            $media = $medias->findOneBy(['id' => $loan->getIdMedia()]);
            if ($media) {
                $mediasArray[] = [
                    'id' => $media->getId(),
                    'title' => $media->getTitle(),
                    'author' => $media->getAuthor(),
                    'type' => $media->getType(),
                    'description' => $media->getDescription(),
                    'image' => $media->getImage(),
                    'status' => $media->isStatus(),
                ];
            }
        }

        $historyArray = [];
        $myHistory = $history->findBy(['id_user' => $user['id']]);

        foreach ($myHistory as $loan) {
            $media = $medias->findOneBy(['id' => $loan->getIdMedia()]);
            if ($media) {
                $historyArray[] = [
                    'id' => $media->getId(),
                    'title' => $media->getTitle(),
                    'author' => $media->getAuthor(),
                    'type' => $media->getType(),
                    'description' => $media->getDescription(),
                    'image' => $media->getImage(),
                    'status' => $media->isStatus(),
                ];
            }
        }

        return $this->json([
            'medias' => $mediasArray,
            'history' => $historyArray,
        ]);
    }
}