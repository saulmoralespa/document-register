<?php

namespace App\Repository;

use App\Entity\TipTipoDoc;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TipTipoDoc>
 */
class TipTipoDocRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipTipoDoc::class);
    }

    /**
     * Find all document types ordered by name
     *
     * @return TipTipoDoc[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.nombre', 'ASC')
            ->getQuery()
            ->getResult();
    }
}

