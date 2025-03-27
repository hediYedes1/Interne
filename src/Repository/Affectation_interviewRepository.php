<?php

namespace App\Repository;

use App\Entity\Affectation_interview;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class Affectation_interviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Affectation_interview::class);
    }

    // Add custom methods as needed
}