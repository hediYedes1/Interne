<?php
// src/Repository/ProjetRepository.php
namespace App\Repository;

use App\Entity\Projet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProjetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Projet::class);
    }

    public function search(string $query = null)
    {
        $qb = $this->createQueryBuilder('p');
        
        if ($query) {
            $qb->where('p.titreprojet LIKE :query OR p.descriptionprojet LIKE :query')
               ->setParameter('query', '%'.$query.'%');
        }
        
        return $qb->getQuery()->getResult();
    }
}