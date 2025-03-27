<?php

namespace App\Repository;

use App\Entity\Commentaire_publication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class Commentaire_publicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentaire_publication::class);
    }

    // Add custom methods as needed
}