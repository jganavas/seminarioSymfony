<?php

namespace App\Repository;

use App\Entity\Writer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Writer>
 */
class WriterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Writer::class);
    }

    /**
     * Find writers by specialty
     *
     * @return Writer[]
     */
    public function findBySpecialty(string $specialty): array
    {
        return $this->createQueryBuilder('w')
            ->where('w.specialty = :specialty')
            ->setParameter('specialty', $specialty)
            ->orderBy('w.firstName', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get all specialties in use
     *
     * @return array<string>
     */
    public function getAllSpecialties(): array
    {
        $result = $this->createQueryBuilder('w')
            ->select('DISTINCT w.specialty')
            ->where('w.specialty IS NOT NULL')
            ->orderBy('w.specialty', 'ASC')
            ->getQuery()
            ->getScalarResult();

        return array_column($result, 'specialty');
    }
}
