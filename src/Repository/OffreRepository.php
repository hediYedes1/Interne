<?php
namespace App\Repository;

use App\Entity\Offre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OffreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offre::class);
    }

    public function getSearchQuery(string $query = null, string $typeContrat = null)
    {
        $qb = $this->createQueryBuilder('o');
        
        if ($query) {
            $qb->andWhere('o.titreoffre LIKE :query OR o.descriptionoffre LIKE :query')
               ->setParameter('query', '%'.$query.'%');
        }
        
        if ($typeContrat && in_array($typeContrat, ['CDI', 'CDD', 'STAGE'])) {
            $qb->andWhere('o.typecontrat = :type')
               ->setParameter('type', $typeContrat);
        }
        
        return $qb->getQuery();
    }
}