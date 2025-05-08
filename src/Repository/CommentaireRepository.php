<?php
namespace App\Repository;

use App\Entity\Commentaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CommentaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentaire::class);
    }

    // Custom query methods can be added here, for example, for fetching comments by publication
    public function findByPublication($publication)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.idPublication = :publication')
            ->setParameter('publication', $publication)
            ->getQuery()
            ->getResult();
    }
}
