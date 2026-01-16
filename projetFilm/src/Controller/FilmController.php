<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\TarifDynamique;
use App\Entity\Favori;
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
        $page = $request->query->getInt('page', 1);
        $limit = 12;
        
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
    public function show(Film $film, EntityManagerInterface $entityManager): Response
    {
        $prixDynamique = $this->calculerPrixDynamique($film->getPrixLocationParDefaut(), $entityManager);
        
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

  
    private function calculerPrixDynamique(string $prixBase, EntityManagerInterface $entityManager): float
    {
        $jour = strtolower(date('l')); 
        
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
        
        if (!$jourSemaine) {
            return (float) $prixBase;
        }
        

        $tarif = $entityManager->getRepository(TarifDynamique::class)
            ->findOneBy(['jourSemaine' => $jourSemaine, 'actif' => true]);
        
        if ($tarif) {
            $reduction = (float) $tarif->getPourcentageReduction();
            $prixBaseFloat = (float) $prixBase;
            return $prixBaseFloat * (1 + $reduction / 100);
        }
        
        return (float) $prixBase;
    }
    #[Route('/{id}/favori/ajouter', name: 'app_film_add_favorite', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function addFavorite(Film $film, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        
        $favoriExist = $entityManager->getRepository(Favori::class)
            ->findOneBy(['utilisateur' => $user, 'film' => $film]);
        
        if (!$favoriExist) {
            $favori = new Favori();
            $favori->setUtilisateur($user);
            $favori->setFilm($film);
            $favori->setDateAjout(new \DateTime());
            
            $entityManager->persist($favori);
            $entityManager->flush();
            
            $this->addFlash('success', 'Film ajouté à vos favoris !');
        } else {
            $this->addFlash('info', 'Ce film est déjà dans vos favoris.');
        }
        
        return $this->redirectToRoute('app_film_show', ['id' => $film->getId()]);
    }

    #[Route('/{id}/favori/retirer', name: 'app_film_remove_favorite', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function removeFavorite(Film $film, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        
        $favori = $entityManager->getRepository(Favori::class)
            ->findOneBy(['utilisateur' => $user, 'film' => $film]);
        
        if ($favori) {
            $entityManager->remove($favori);
            $entityManager->flush();
            
            $this->addFlash('success', 'Film retiré de vos favoris.');
        }
        
        return $this->redirectToRoute('app_profile_favoris');
    }

    // Dans FilmController.php, ajoutez cette méthode :

#[Route('/{id}/panier/ajouter', name: 'app_film_add_to_cart', methods: ['POST'])]
#[IsGranted('ROLE_USER')]
public function addToCart(Film $film, Request $request, EntityManagerInterface $entityManager): Response
{
    $quantite = (int) $request->request->get('quantite', 1);
    
    if ($quantite < 1) {
        $quantite = 1;
    }
    
    // Récupérer ou créer le panier dans la session
    $panier = $request->getSession()->get('panier', []);
    
    $filmId = $film->getId();
    
    $prixDynamique = $this->calculerPrixDynamique($film->getPrixLocationParDefaut(), $entityManager);
    
    if (isset($panier[$filmId])) {
        $panier[$filmId]['quantite'] += $quantite;
    } else {
        $panier[$filmId] = [
            'id' => $filmId,
            'titre' => $film->getTitre(),
            'affiche' => $film->getCheminAffiche(),
            'annee' => $film->getAnnee(),
            'genre' => $film->getGenre(),
            'prix_base' => (float) $film->getPrixLocationParDefaut(),
            'prix_dynamique' => $prixDynamique,
            'quantite' => $quantite,
            'duree' => $film->getDuree()
        ];
    }
    
    $request->getSession()->set('panier', $panier);
    
    $this->addFlash('success', sprintf('"%s" a été ajouté à votre panier', $film->getTitre()));
    
    return $this->redirectToRoute('app_film_show', ['id' => $filmId]);
}

}