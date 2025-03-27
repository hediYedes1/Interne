<?php

namespace App\Repository;

use App\Entity\Affectation_hebergement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class Affectation_hebergementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Affectation_hebergement::class);
    }

    // Add custom methods as needed
}