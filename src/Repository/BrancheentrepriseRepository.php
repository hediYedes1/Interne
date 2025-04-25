<?php

namespace App\Repository;

use App\Entity\Brancheentreprise;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Brancheentreprise>
 *
 * @method Brancheentreprise|null find($id, $lockMode = null, $lockVersion = null)
 * @method Brancheentreprise|null findOneBy(array $criteria, array $orderBy = null)
 * @method Brancheentreprise[]    findAll()
 * @method Brancheentreprise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrancheentrepriseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Brancheentreprise::class);
    }

    
}
