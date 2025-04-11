<?php

namespace App\Repository;

use App\Entity\Reponsecommentairepublication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReponsecommentairepublicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reponsecommentairepublication::class);
    }

    // Add custom methods as needed
}