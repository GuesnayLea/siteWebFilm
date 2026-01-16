<?php
namespace App\Controller;

use App\Entity\Film;
use App\Entity\Location;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/panier')]
#[IsGranted('ROLE_USER')]
class CartController extends AbstractController
{
    #[Route('/', name: 'app_cart_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $panier = $request->getSession()->get('panier', []);
        
        $totalPanier = 0;
        $nombreArticles = 0;
        
        foreach ($panier as $item) {
            $totalPanier += $item['prix_dynamique'] * $item['quantite'];
            $nombreArticles += $item['quantite'];
        }
        
        $request->getSession()->set('panier_count', $nombreArticles);
        
        return $this->render('cart/index.html.twig', [
            'panier' => $panier,
            'totalPanier' => $totalPanier,
            'nombreArticles' => $nombreArticles,
        ]);
    }

    #[Route('/ajouter/{id}', name: 'app_cart_add', methods: ['POST'])]
    public function add(Film $film, Request $request, EntityManagerInterface $entityManager): Response
    {
        $quantite = (int) $request->request->get('quantite', 1);
        
        if ($quantite < 1) {
            $quantite = 1;
        }
        
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
        
        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/retirer/{id}', name: 'app_cart_remove', methods: ['POST'])]
    public function remove(Film $film, Request $request): Response
    {
        $panier = $request->getSession()->get('panier', []);
        $filmId = $film->getId();
        
        if (isset($panier[$filmId])) {
            unset($panier[$filmId]);
            $request->getSession()->set('panier', $panier);
            $this->addFlash('success', sprintf('"%s" a été retiré de votre panier', $film->getTitre()));
        }
        
        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/mettre-a-jour/{id}', name: 'app_cart_update', methods: ['POST'])]
    public function update(Film $film, Request $request): Response
    {
        $quantite = (int) $request->request->get('quantite', 1);
        
        if ($quantite < 0) {
            $quantite = 0;
        }
        
        $panier = $request->getSession()->get('panier', []);
        $filmId = $film->getId();
        
        if (isset($panier[$filmId])) {
            if ($quantite === 0) {
                unset($panier[$filmId]);
                $this->addFlash('success', sprintf('"%s" a été retiré de votre panier', $film->getTitre()));
            } else {
                $panier[$filmId]['quantite'] = $quantite;
                $this->addFlash('success', 'Quantité mise à jour');
            }
            
            $request->getSession()->set('panier', $panier);
        }
        
        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/vider', name: 'app_cart_clear', methods: ['POST'])]
    public function clear(Request $request): Response
    {
        $request->getSession()->remove('panier');
        $request->getSession()->remove('panier_count');
        
        $this->addFlash('success', 'Votre panier a été vidé');
        
        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/paiement', name: 'app_cart_checkout', methods: ['GET'])]
    public function checkout(Request $request, EntityManagerInterface $entityManager): Response
    {
        $panier = $request->getSession()->get('panier', []);
        
        if (empty($panier)) {
            $this->addFlash('warning', 'Votre panier est vide');
            return $this->redirectToRoute('app_cart_index');
        }
        
        $totalPanier = 0;
        foreach ($panier as &$item) {
            $film = $entityManager->getRepository(Film::class)->find($item['id']);
            if ($film) {
                $item['prix_dynamique'] = $this->calculerPrixDynamique($film->getPrixLocationParDefaut(), $entityManager);
            }
            $totalPanier += $item['prix_dynamique'] * $item['quantite'];
        }
        
        return $this->render('cart/checkout.html.twig', [
            'panier' => $panier,
            'totalPanier' => $totalPanier,
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/confirmer-paiement', name: 'app_cart_confirm_payment', methods: ['POST'])]
    public function confirmPayment(Request $request, EntityManagerInterface $entityManager): Response
    {
        $panier = $request->getSession()->get('panier', []);
        $user = $this->getUser();
        
        if (empty($panier)) {
            $this->addFlash('warning', 'Votre panier est vide');
            return $this->redirectToRoute('app_cart_index');
        }
        
        foreach ($panier as $item) {
            $film = $entityManager->getRepository(Film::class)->find($item['id']);
            
            if ($film) {
                for ($i = 0; $i < $item['quantite']; $i++) {
                    $location = new Location();
                    $location->setUtilisateur($user);
                    $location->setFilm($film);
                    $location->setPrixFinal((string) $item['prix_dynamique']);
                    $location->setStatut('loué');
                    
                    $entityManager->persist($location);
                }
            }
        }
        
        $entityManager->flush();
        
        $request->getSession()->remove('panier');
        $request->getSession()->remove('panier_count');
        
        $this->addFlash('success', 'Paiement confirmé ! Vos locations ont été enregistrées.');
        
        return $this->redirectToRoute('app_profile');
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
        
        $tarif = $entityManager->getRepository(\App\Entity\TarifDynamique::class)
            ->findOneBy(['jourSemaine' => $jourSemaine, 'actif' => true]);
        
        if ($tarif) {
            $reduction = (float) $tarif->getPourcentageReduction();
            $prixBaseFloat = (float) $prixBase;
            return $prixBaseFloat * (1 + $reduction / 100);
        }
        
        return (float) $prixBase;
    }
}