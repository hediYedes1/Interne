<?php

namespace App\Repository;

use App\Entity\Testtechnique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Interview;

class TesttechniqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Testtechnique::class);
    }

    
    public function findByTitretesttechnique(string $titre): array
    {
        return $this->createQueryBuilder('i')
            ->where('LOWER(i.titretesttechnique) LIKE :titre')
            ->setParameter('titre', '%' . mb_strtolower($titre) . '%')
            ->getQuery()
            ->getResult();
    }
   // Dans TesttechniqueRepository.php
public function findByFiltersForInterview(Interview $interview, ?string $titre, ?string $statut): array
{
    $qb = $this->createQueryBuilder('t')
        ->where('t.idinterview = :interview')
        ->setParameter('interview', $interview);

    if ($titre) {
        $qb->andWhere('t.titretesttechnique LIKE :titre')
           ->setParameter('titre', '%'.$titre.'%');
    }

    if ($statut) {
        $qb->andWhere('t.statuttesttechnique = :statut')
           ->setParameter('statut', $statut);
    }

    return $qb->getQuery()->getResult();
}
}