<?php

namespace App\Repository;

use App\Entity\DocDocumento;
use App\Entity\ProProceso;
use App\Entity\TipTipoDoc;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DocDocumento>
 */
class DocDocumentoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DocDocumento::class);
    }

    /**
     * Find the highest consecutive number for a given tipo and proceso combination
     */
    public function findMaxConsecutive(TipTipoDoc $tipo, ProProceso $proceso): int
    {
        $prefix = $tipo->getPrefijo() . '-' . $proceso->getPrefijo() . '-';

        $qb = $this->createQueryBuilder('d')
            ->select('d.codigo')
            ->where('d.tipo = :tipo')
            ->andWhere('d.proceso = :proceso')
            ->andWhere('d.codigo LIKE :prefix')
            ->setParameter('tipo', $tipo)
            ->setParameter('proceso', $proceso)
            ->setParameter('prefix', $prefix . '%')
            ->orderBy('d.id', 'DESC');

        $results = $qb->getQuery()->getResult();

        $maxConsecutive = 0;
        foreach ($results as $result) {
            $codigo = $result['codigo'];
            // Extract the consecutive number from the code (format: TIP-PRO-123)
            $parts = explode('-', $codigo);
            if (count($parts) === 3 && is_numeric($parts[2])) {
                $consecutive = (int) $parts[2];
                if ($consecutive > $maxConsecutive) {
                    $maxConsecutive = $consecutive;
                }
            }
        }

        return $maxConsecutive;
    }

    /**
     * Search documents by name or code
     *
     * @return DocDocumento[]
     */
    public function search(?string $query): array
    {
        $qb = $this->createQueryBuilder('d')
            ->leftJoin('d.tipo', 't')
            ->leftJoin('d.proceso', 'p')
            ->addSelect('t', 'p')
            ->orderBy('d.id', 'DESC');

        if ($query) {
            $qb->andWhere('d.nombre LIKE :query OR d.codigo LIKE :query')
                ->setParameter('query', '%' . $query . '%');
        }

        return $qb->getQuery()->getResult();
    }
}

