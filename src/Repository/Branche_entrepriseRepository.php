<?php

namespace App\Repository;

use App\Entity\Branche_entreprise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class Branche_entrepriseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Branche_entreprise::class);
    }

    // Add custom methods as needed
}