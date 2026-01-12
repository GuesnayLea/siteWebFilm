<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/profil')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_profile')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        $user = $this->getUser();
        
        return $this->render('user/profile.html.twig', [
            'user' => $user,
        ]);
    }
    
    #[Route('/favoris', name: 'app_profile_favoris')]
    #[IsGranted('ROLE_USER')]
    public function favoris(): Response
    {
        return $this->render('user/favoris.html.twig');
    }
    
    #[Route('/historique', name: 'app_profile_historique')]
    #[IsGranted('ROLE_USER')]
    public function historique(): Response
    {
        return $this->render('user/historique.html.twig');
    }
}