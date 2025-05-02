<?php

namespace App\ControllerAPI;

use App\Repository\MediasRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

final class HomeControllerAPI extends AbstractController
{
    #[Route('/api/home', name: 'api_home', methods: ['GET'])]
    public function index(SessionInterface $session, MediasRepository $medias): JsonResponse
    {
        $user = $session->get('user');
        $medias = $medias->findAll();

        return $this->json([
            'page_name' => 'Accueil',
            'user' => $user,
            'medias' => $medias,
        ]);
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request, SessionInterface $session, UserRepository $user): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'];
        $password = $data['password'];

        if (!$email || !$password) {
            return $this->json(['error' => 'Les champs sont obligatoires'], 400);
        }

        if ($user->checkLogsin($email, $password)) {
            $userDetails = $user->getUserDetails($email);

            if ($userDetails) {
                $session->set('user', [
                    'id' => $userDetails['id'],
                    'firstname' => $userDetails['firstname'],
                    'lastname' => $userDetails['lastname'],
                    'email' => $userDetails['email'],
                    'status' => $userDetails['status'],
                ]);

                return $this->json(['message' => 'Connexion reussie', 'user' => $userDetails]);
            }
        }

        return $this->json(['error' => 'Invalid credentials'], 401);
    }

    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request, UserRepository $user): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $email = $data['email'];
        $password = $data['password'];

        if (!$firstname || !$lastname || !$email || !$password) {
            return $this->json(['error' => 'Les champs sont obligatoires'], 400);
        }

        if ($user->add($firstname, $lastname, $email, $password)) {
            return $this->json(['message' => 'Inscription reussie']);
        }

        return $this->json(['error' => 'Erreur inscription'], 500);
    }

    #[Route('/api/logout', name: 'api_logout', methods: ['POST'])]
    public function logout(SessionInterface $session): JsonResponse
    {
        $session->remove('user');
        return $this->json(['message' => 'Deconnexion reussie']);
    }
}