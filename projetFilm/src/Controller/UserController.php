<?php

namespace App\Controller;

use App\Entity\Favori;
use App\Repository\FilmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function favoris(EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $this->getUser();
        $page = $request->query->getInt('page', 1);
        $limit = 9;
        
        $query = $entityManager->getRepository(Favori::class)
            ->createQueryBuilder('f')
            ->where('f.utilisateur = :user')
            ->setParameter('user', $user)
            ->orderBy('f.dateAjout', 'DESC')
            ->getQuery();
        
        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        
        $totalFavoris = count($paginator);
        $totalPages = ceil($totalFavoris / $limit);
        
        $films = [];
        foreach ($paginator as $favori) {
            $films[] = [
                'film' => $favori->getFilm(),
                'dateAjout' => $favori->getDateAjout()
            ];
        }
        
        return $this->render('user/favoris.html.twig', [
            'films' => $films,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalFavoris' => $totalFavoris,
        ]);
    }
    
    #[Route('/historique', name: 'app_profile_historique')]
    #[IsGranted('ROLE_USER')]
    public function historique(): Response
    {
        return $this->render('user/historique.html.twig');
    }
}