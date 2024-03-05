<?php

namespace App\Repository;

use App\Entity\StatsRequestClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StatsRequestClient>
 *
 * @method StatsRequestClient|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatsRequestClient|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatsRequestClient[]    findAll()
 * @method StatsRequestClient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatsRequestClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatsRequestClient::class);
    }

//    /**
//     * @return StatsRequestClient[] Returns an array of StatsRequestClient objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?StatsRequestClient
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
