<?php

namespace App\Repository;

use App\Entity\Interview;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Enum\TypeInterview;
class InterviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Interview::class);
    }
  
    public function findByTitreoffre(string $titre): array
    {
        return $this->createQueryBuilder('i')
            ->where('LOWER(i.titreoffre) LIKE :titre')
            ->setParameter('titre', '%' . mb_strtolower($titre) . '%')
            ->getQuery()
            ->getResult();
    }
    public function findByFilters(?string $titre = null, ?string $type = null): array
    {
        $qb = $this->createQueryBuilder('i');
        
        if ($titre) {
            $qb->andWhere('i.titreoffre LIKE :titre')
               ->setParameter('titre', '%'.$titre.'%');
        }
        
        if ($type) {
            $qb->andWhere('i.typeinterview = :type')
               ->setParameter('type', $type);
        }
        
        return $qb->getQuery()->getResult();
    }
    
    // src/Repository/InterviewRepository.php
    public function findInterviewsInNext24Hours(): array
    {
        $now = new \DateTime();
        $in24Hours = (new \DateTime())->add(new \DateInterval('PT24H'));
    
        return $this->createQueryBuilder('i')
            ->where('i.dateinterview BETWEEN :now AND :in24Hours')
            ->andWhere('i.timeinterview IS NOT NULL')
            ->setParameter('now', $now)
            ->setParameter('in24Hours', $in24Hours)
            ->getQuery()
            ->getResult();
    }

    public function getStatisticsByType(): array
    {
        return $this->createQueryBuilder('i')
            ->select([
                'i.typeinterview as type',
                'COUNT(i.idinterview) as count',
                'MIN(i.dateinterview) as earliest_date',
                'MAX(i.dateinterview) as latest_date'
            ])
            ->groupBy('i.typeinterview')
            ->getQuery()
            ->getResult();
    }
}