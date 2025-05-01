<?php

namespace App\Repository;

use App\Entity\Interview;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class InterviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Interview::class);
    }

    /**
     * Filtre les interviews sans tenir compte de l'utilisateur.
     */
    public function findByFilters(?string $titre = null, ?string $type = null): array
    {
        $qb = $this->createQueryBuilder('i');

        if ($titre) {
            $qb->andWhere('i.titreoffre LIKE :titre')
                ->setParameter('titre', '%' . $titre . '%');
        }

        if ($type) {
            $qb->andWhere('i.typeinterview = :type')
                ->setParameter('type', $type);
        }

        return $qb->orderBy('i.dateinterview', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Filtre les interviews selon un utilisateur donné.
     */
    public function findInterviewsByUserWithFilters(Utilisateur $user, ?string $titre = null, ?string $type = null): array
    {
        $qb = $this->createQueryBuilder('i')
            ->innerJoin('i.affectationinterviews', 'a') // assure-toi que la relation existe
            ->where('a.idutilisateur = :user')
            ->setParameter('user', $user);

        if ($titre) {
            $qb->andWhere('i.titreoffre LIKE :titre')
                ->setParameter('titre', '%' . $titre . '%');
        }

        if ($type) {
            $qb->andWhere('i.typeinterview = :type')
                ->setParameter('type', $type);
        }

        return $qb->orderBy('i.dateinterview', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByTitreoffre(string $titre): array
    {
        return $this->createQueryBuilder('i')
            ->where('LOWER(i.titreoffre) LIKE :titre')
            ->setParameter('titre', '%' . mb_strtolower($titre) . '%')
            ->getQuery()
            ->getResult();
    }

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
