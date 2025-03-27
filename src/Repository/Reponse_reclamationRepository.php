<?php

namespace App\Repository;

use App\Entity\Reponse_reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class Reponse_reclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reponse_reclamation::class);
    }

    // Add custom methods as needed
}