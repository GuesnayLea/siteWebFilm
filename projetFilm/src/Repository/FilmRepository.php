<?php

namespace App\Repository;

use App\Entity\Film;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Film>
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }

    public function paginate($query, $page = 1, $limit = 12)
    {
        $paginator = new Paginator($query);
        
        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
            
        return $paginator;
    }

    public function findDistinctGenres(): array
    {
        return $this->createQueryBuilder('f')
            ->select('DISTINCT f.genre')
            ->where('f.genre IS NOT NULL')
            ->orderBy('f.genre', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();
    }

    public function findDistinctAnnees(): array
    {
        return $this->createQueryBuilder('f')
            ->select('DISTINCT f.annee')
            ->orderBy('f.annee', 'DESC')
            ->getQuery()
            ->getSingleColumnResult();
    }
}