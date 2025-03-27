<?php

namespace App\Repository;

use App\Entity\Affectation_interview1;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class Affectation_interview1Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Affectation_interview1::class);
    }

    // Add custom methods as needed
}