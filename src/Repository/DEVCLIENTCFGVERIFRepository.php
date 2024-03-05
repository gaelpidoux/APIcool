<?php

namespace App\Repository;

use App\Entity\DEVCLIENTCFGVERIF;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DEVCLIENTCFGVERIF>
 *
 * @method DEVCLIENTCFGVERIF|null find($id, $lockMode = null, $lockVersion = null)
 * @method DEVCLIENTCFGVERIF|null findOneBy(array $criteria, array $orderBy = null)
 * @method DEVCLIENTCFGVERIF[]    findAll()
 * @method DEVCLIENTCFGVERIF[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DEVCLIENTCFGVERIFRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DEVCLIENTCFGVERIF::class);
    }

//    /**
//     * @return DEVCLIENTCFGVERIF[] Returns an array of DEVCLIENTCFGVERIF objects
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

//    public function findOneBySomeField($value): ?DEVCLIENTCFGVERIF
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
