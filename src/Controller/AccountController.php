<?php

namespace App\Controller;


use App\Repository\HistoryRepository;
use App\Repository\LoanRepository;
use App\Repository\MediasRepository;
use SessionIdInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{
    #[Route('compte/', name: 'account', methods: ['GET'])]
    function index(SessionInterface $session, MediasRepository $medias, HistoryRepository $history): Response {
        
        $user = $session->get('user');

        //on renvoi a la page d'accueil si pas de user dans la session
        if ($user === null) {
            return $this->redirectToRoute('home');
        }

        //array pour stocker les medias
        $mediasArray = [];

        $myHistory = $history->findBy(['id_user' => $user['id']]);

        foreach ($myHistory as $loan) {
            //pour chaque emprunt on va chercher le media correspondant
            $media = $medias->findOneBy(['id' => ($loan->getIdMedia())]);
            //evite les doublons
            if ($media ) {
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

        return $this->render('account/index.html.twig', [
            'page_name' => 'Mon compte',
            'user' => $user,
            'medias' => $mediasArray,
        ]);

    }

    #[Route('emprunts/', name: 'history', methods: ['GET'])]
    function loans(SessionInterface $session, MediasRepository $medias, LoanRepository $loans, HistoryRepository $history): Response {
        //on initialise user sinon on a une erreur
        $user= null;

        $user = $session->get('user');

        if ($user === null) {
            return $this->redirectToRoute('home');
        }

        //array pour stocker les medias
        $mediasArray = [];

        $myLoans = $loans->findBy(['idUser' => $user['id']]);

        foreach ($myLoans as $loan) {
            //pour chaque emprunt on va chercher le media correspondant
            $media = $medias->findOneby(['id' => ($loan->getIdMedia())]);
            if ($media) {
                $mediasArray[] = $media;
            }
        }

        $historyArray = [];
        $myHistory = $history->findBy(['id_user' => $user['id']]);

        foreach ($myHistory as $loan) {
            //pour chaque emprunt on va chercher le media correspondant
            $media = $medias->findOneby(['id' => ($loan->getIdMedia())]);
            if ($media) {
                $historyArray[] = $media;
            }
        }

        return $this->render('account/history.html.twig', [
            'page_name' => 'Mes emprunts',
            'user' => $user,
            'medias' => $mediasArray,
            'history' => $historyArray,
        ]);
    }
}
