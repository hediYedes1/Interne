<?php

namespace App\Repository;

use App\Entity\Interview;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Enum\TypeInterview;
use App\Entity\Utilisateur;
class InterviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Interview::class);
    }
  
    public function findInterviewsByUserWithFilters(Utilisateur $user, ?string $titre = null, ?string $type = null): array
    {
        $qb = $this->createQueryBuilder('i')
            ->innerJoin('i.affectationinterviews', 'a') // Assurez-vous que c'est bien le nom de la relation
            ->where('a.idutilisateur = :user')
            ->setParameter('user', $user);
        
        if ($titre) {
            $qb->andWhere('i.titreoffre LIKE :titre')
               ->setParameter('titre', '%'.$titre.'%');
        }
        
        if ($type) {
            $qb->andWhere('i.typeinterview = :type')
               ->setParameter('type', $type);
        }
        
        return $qb->orderBy('i.dateinterview', 'ASC')
                  ->getQuery()
                  ->getResult();
    }

    // Vous pouvez garder les autres méthodes si elles sont utilisées ailleurs
    public function findByTitreoffre(string $titre): array
    {
        return $this->createQueryBuilder('i')
            ->where('LOWER(i.titreoffre) LIKE :titre')
            ->setParameter('titre', '%'.mb_strtolower($titre).'%')
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

    
    

    // Add custom methods as needed
}