<?php

namespace App\Repository;

use App\Entity\DEVCLIENTPRINCIPALVERIF;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DEVCLIENTPRINCIPALVERIF>
 *
 * @method DEVCLIENTPRINCIPALVERIF|null find($id, $lockMode = null, $lockVersion = null)
 * @method DEVCLIENTPRINCIPALVERIF|null findOneBy(array $criteria, array $orderBy = null)
 * @method DEVCLIENTPRINCIPALVERIF[]    findAll()
 * @method DEVCLIENTPRINCIPALVERIF[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DEVCLIENTPRINCIPALVERIFRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DEVCLIENTPRINCIPALVERIF::class);
    }

//    /**
//     * @return DEVCLIENTPRINCIPALVERIF[] Returns an array of DEVCLIENTPRINCIPALVERIF objects
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

//    public function findOneBySomeField($value): ?DEVCLIENTPRINCIPALVERIF
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
