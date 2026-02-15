<?php

namespace App\Repository;

use App\Entity\Administrator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Administrator>
 */
class AdministratorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Administrator::class);
    }

    /**
     * Find administrators by minimum permission level
     *
     * @return Administrator[]
     */
    public function findByMinimumPermissionLevel(int $minLevel): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.permissionLevel >= :minLevel')
            ->setParameter('minLevel', $minLevel)
            ->orderBy('a.permissionLevel', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find administrators with specific permission level
     *
     * @return Administrator[]
     */
    public function findByPermissionLevel(int $level): array
    {
        return $this->findBy(['permissionLevel' => $level], ['firstName' => 'ASC']);
    }
}
