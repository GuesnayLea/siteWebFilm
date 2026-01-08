<?php

namespace App\Controller;

use App\Entity\Film;
use App\Form\FilmType;
use App\Repository\FilmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/films')]
class FilmController extends AbstractController
{
    #[Route('/', name: 'app_film_index', methods: ['GET'])]
    public function index(FilmRepository $filmRepository, Request $request): Response
    {
        // Pagination
        $page = $request->query->getInt('page', 1);
        $limit = 12;
        
        // Filtres
        $genre = $request->query->get('genre');
        $annee = $request->query->get('annee');
        
        $query = $filmRepository->createQueryBuilder('f');
        
        if ($genre) {
            $query->andWhere('f.genre = :genre')
                  ->setParameter('genre', $genre);
        }
        
        if ($annee) {
            $query->andWhere('f.annee = :annee')
                  ->setParameter('annee', $annee);
        }
        
        $paginator = $filmRepository->paginate($query->getQuery(), $page, $limit);
        
        // Liste des genres pour le filtre
        $genres = $filmRepository->findDistinctGenres();
        $annees = $filmRepository->findDistinctAnnees();
        
        return $this->render('film/index.html.twig', [
            'films' => $paginator,
            'genres' => $genres,
            'annees' => $annees,
        ]);
    }

    #[Route('/nouveau', name: 'app_film_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $film = new Film();
        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($film);
            $entityManager->flush();

            $this->addFlash('success', 'Film ajouté avec succès!');
            return $this->redirectToRoute('app_film_show', ['id' => $film->getId()]);
        }

        return $this->render('film/new.html.twig', [
            'film' => $film,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_film_show', methods: ['GET'])]
    public function show(Film $film): Response
    {
        // Calcul du prix dynamique
        $prixDynamique = $this->calculerPrixDynamique($film->getPrixLocationParDefaut());
        
        return $this->render('film/show.html.twig', [
            'film' => $film,
            'prixDynamique' => $prixDynamique,
        ]);
    }

    #[Route('/{id}/modifier', name: 'app_film_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Film $film, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Film modifié avec succès!');
            return $this->redirectToRoute('app_film_show', ['id' => $film->getId()]);
        }

        return $this->render('film/edit.html.twig', [
            'film' => $film,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_film_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Film $film, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$film->getId(), $request->request->get('_token'))) {
            $entityManager->remove($film);
            $entityManager->flush();
            
            $this->addFlash('success', 'Film supprimé avec succès!');
        }

        return $this->redirectToRoute('app_film_index');
    }

    private function calculerPrixDynamique(float $prixBase): float
    {
        $jour = strtolower(date('l')); // 'monday', 'tuesday', etc.
        
        // Convertissez en français
        $joursMapping = [
            'monday' => 'lundi',
            'tuesday' => 'mardi',
            'wednesday' => 'mercredi',
            'thursday' => 'jeudi',
            'friday' => 'vendredi',
            'saturday' => 'samedi',
            'sunday' => 'dimanche'
        ];
        
        $jourSemaine = $joursMapping[$jour] ?? null;
        
        // Récupérez la réduction depuis la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $tarif = $entityManager->getRepository(\App\Entity\TarifDynamique::class)
            ->findOneBy(['jourSemaine' => $jourSemaine, 'actif' => true]);
        
        if ($tarif) {
            $reduction = $tarif->getPourcentageReduction();
            return $prixBase * (1 + $reduction / 100);
        }
        
        return $prixBase;
    }
}