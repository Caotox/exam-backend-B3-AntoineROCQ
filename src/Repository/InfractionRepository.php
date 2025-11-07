<?php

namespace App\Repository;

use App\Entity\Infraction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class InfractionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Infraction::class);
    }

    public function findByFilters(?int $ecurieId = null, ?int $piloteId = null, ?string $date = null): array
    {
        $qb = $this->createQueryBuilder('i')
            ->leftJoin('i.pilote', 'p')
            ->leftJoin('i.ecurie', 'e')
            ->addSelect('p', 'e');

        if ($ecurieId) {
            $qb->andWhere('i.ecurie = :ecurieId')
               ->setParameter('ecurieId', $ecurieId);
        }

        if ($piloteId) {
            $qb->andWhere('i.pilote = :piloteId')
               ->setParameter('piloteId', $piloteId);
        }

        if ($date) {
            $qb->andWhere('DATE(i.dateInfraction) = :date')
               ->setParameter('date', $date);
        }

        return $qb->orderBy('i.dateInfraction', 'DESC')
                  ->getQuery()
                  ->getResult();
    }
}
