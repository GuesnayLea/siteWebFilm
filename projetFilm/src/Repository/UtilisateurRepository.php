<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Utilisateur>
 */
class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    // Vous pouvez ajouter vos méthodes personnalisées ici
    // Exemple : trouver un utilisateur par email
    public function findByEmail(string $email): ?Utilisateur
    {
        return $this->findOneBy(['email' => $email]);
    }
    
    // Exemple : trouver des utilisateurs par nom
    public function findByNom(string $nom): array
    {
        return $this->findBy(['nom' => $nom]);
    }
}