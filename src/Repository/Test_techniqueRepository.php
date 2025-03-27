<?php

namespace App\Repository;

use App\Entity\Test_technique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class Test_techniqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Test_technique::class);
    }

    // Add custom methods as needed
}