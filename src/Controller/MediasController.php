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


#[Route('medias/{id}/', name: 'home_id', methods: ['GET', 'POST'])]
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
            if ($user === null) {
                //si le user n'est pas connécté et qu'il veut amprunter, ca lui demande de se connecter
                return $this->redirectToRoute('connexion');
            }
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

#[Route ('medias/{id}/modification/', name: 'modification', methods: ['GET', 'POST'])]
    function modificationMedia (SessionInterface $session,int $id, Request $request, MediasRepository $medias){

        $user = $session->get('user');
        
        if ($user === null || $user['status'] !== 1) {
            return $this->redirectToRoute('connexion');
        }
        
        $media = $medias->find($id);

        //si c en post c'est que c'est le form de modification soumis
        if ($request->getMethod() === 'POST') {
            $title = $request->request->get('title');
            $author = $request->request->get('author');
            $type = $request->request->get('type');
            $description = $request->request->get('description');
            $image = $request->request->get('image');

            //si le form est soumis, on modifie le media
            if ($medias->update($id, $title, $author, $type, $description,  $image)) {
                return $this->redirectToRoute('home');
            } 
        }

        return $this->render('medias/modif.html.twig', [
            'page_name' => 'Accueil',
            'user' => $user,
            'media' => $media,
        ]);
    }

#[Route ('medias/{id}/delete/', name: 'suppression', methods: ['GET'])]
    function deleteMedia (SessionInterface $session,int $id, Request $request, MediasRepository $medias){

        $user = $session->get('user');
        
        if ($user === null || $user['status'] !== 1) {
            return $this->redirectToRoute('connexion');
        }

        //si le form est soumis on supprime le media
        $medias->delete($id);

        return $this->redirectToRoute('home');
    }

#[Route ('/add', name: 'ajout', methods: ['GET', 'POST'])]
    function addMedia (SessionInterface $session, Request $request, MediasRepository $medias){

        $user = $session->get('user');
        
        if ($user === null || $user['status'] !== 1) {
            return $this->redirectToRoute('connexion');
        }

        if ($request->getMethod() === 'POST') {
            $title = $request->request->get('title');
            $author = $request->request->get('author');
            $type = $request->request->get('type');
            $description = $request->request->get('description');
            $image = $request->request->get('image');

            //si le form est soumis, on modifie le media
            if ($medias->add($title, $author, $type, $description,  $image)) {
                return $this->redirectToRoute('home');
            } 
        }

        return $this->render('medias/add.html.twig', [
            'page_name' => 'Ajout Media',
            'user' => $user,
        ]);
    }

}


