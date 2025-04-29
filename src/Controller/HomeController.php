<?php

namespace App\Controller;

use App\Repository\MediasRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController{

    #[Route('/', name: 'home', methods: ['GET', 'POST'])]
    function index(Request $request, SessionInterface $session, MediasRepository $medias, UserRepository $user): Response {

        $medias = $medias->findAll();

        if ($request->getMethod() === 'POST' && $session->get('connexion') === true) {
            //récupère les données du formulaire
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            //vérifie si l'utilisateur existe
            if ($user->checkLogsin($email, $password)) {
                // Fetch user details from the database
                $userDetails = $user->getUserDetails($email);
            
                if ($userDetails) {
                    // Save user details in the session
                    $session->set('user', [
                        'id' => $userDetails['id'],
                        'firstname' => $userDetails['firstname'],
                        'lastname' => $userDetails['lastname'],
                        'email' => $userDetails['email'],
                        'password' => $userDetails['password'],
                        'status' => $userDetails['status'], 
                    ]);
            
                    return $this->redirectToRoute('home');
                }
            }
            else {
                // on met error en session pour afficher le message d'erreur dans connexion et inscription
                $session->set('error', 'Identifiants incorrects');
                return $this->redirectToRoute('connexion');
            }
            
        }
        else if ($request->getMethod() === 'POST' && $session->get('connexion') === false) {
            //récupère les données du formulaire
            $firstname = $request->request->get('firstname');
            $lastname = $request->request->get('lastname');
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            
            //vérifie si l'utilisateur existe
            if ($user->add($firstname, $lastname, $email, $password)) {
                return $this->redirectToRoute('home');
            } 
            else {
                // on met error en session pour afficher le message d'erreur dans connexion et inscription
                $session->set('error', 'Identifiants incorrects');
                return $this->redirectToRoute('inscription');
            }
        }

        // Récupère l'utilisateur connecté avce la session (pas avec des cookies)
        $user = $session->get('user');


        return $this->render('home/index.html.twig', [
            //render la page avec le titre et les infos de l'utilisateur
            'page_name' => 'Accueil',
            'user' => $user,
            'medias' => $medias
        ]);
    }

    //rout pour se delog en renvoyer ver la page d'accueil
    #[Route('/deconnexion', name: 'logout', methods: ['GET'])]
        public function logout(SessionInterface $session): Response {

            //Supprime l'utilisateur de la session
            $session->remove('user');

            return $this->redirectToRoute('home');
        }   
    
}