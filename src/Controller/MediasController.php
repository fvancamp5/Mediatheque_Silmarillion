<?php

namespace App\Controller;

use App\Repository\LoanRepository;
use App\Repository\MediasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class MediasController extends AbstractController{


#[Route('medias/{id}', name: 'home_id', methods: ['GET', 'POST'])]
    function indexMedia(SessionInterface $session,int $id, Request $request, MediasRepository $medias, LoanRepository $loan): Response {

        // Récupère l'utilisateur connecté avce la session (pas avec des cookies)
        $user = $session->get('user');
        

        //condition si l'emprunt appartient au user pour afficher le bouton de retour (on met a false de base pour permettre aux user de voir sans se log)
        $condition = false;

        if ($user !== null) {
            $condition = $medias->isLoan($user['id'], $id);
        }


        $media = $medias->find($id);

        if ($request->getMethod() === 'POST') {
            if ($request->request->get('emprunter')){
                //emprunter le media
                if ($loan->add($user['id'], $id)) {
                    return $this->redirectToRoute('home_id', ['id' => $id]);
                } 
            }
            else if ($request->request->get('retourner')){
                //retourner le media
                if ($loan->return($id)) {
                    return $this->redirectToRoute('home_id', ['id' => $id]);
                } 
            }
        }
    
        return $this->render('medias/index.html.twig', [
            'page_name' => 'Accueil',
            'user' => $user,
            'media' => $media,
            'condition' => $condition,
        ]);
    }
}