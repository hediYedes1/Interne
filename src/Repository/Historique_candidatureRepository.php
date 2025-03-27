<?php

namespace App\Repository;

use App\Entity\Historique_candidature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class Historique_candidatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Historique_candidature::class);
    }

    // Add custom methods as needed
}