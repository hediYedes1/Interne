<?php

namespace App\Repository;

use App\Entity\Reponse_commentaire_publication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class Reponse_commentaire_publicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reponse_commentaire_publication::class);
    }

    // Add custom methods as needed
}