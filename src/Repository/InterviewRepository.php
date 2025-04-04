<?php

namespace App\Repository;

use App\Entity\Interview;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class InterviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Interview::class);
    }
  
    public function findByTitreOffre(string $titre): array
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT i FROM App\Entity\Interview i
                WHERE i.titreoffre LIKE :titre
            ')
            ->setParameter('titre', '%' . $titre . '%')
            ->getResult();
    }
    

    // Add custom methods as needed
}