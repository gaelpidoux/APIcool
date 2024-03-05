<?php

namespace App\Repository;

use App\Entity\DataClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DataClient>
 *
 * @method DataClient|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataClient|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataClient[]    findAll()
 * @method DataClient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataClient::class);
    }

//    /**
//     * @return DataClient[] Returns an array of DataClient objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DataClient
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
