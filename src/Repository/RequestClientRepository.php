<?php

namespace App\Repository;

use App\Entity\RequestClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RequestClient>
 *
 * @method RequestClient|null find($id, $lockMode = null, $lockVersion = null)
 * @method RequestClient|null findOneBy(array $criteria, array $orderBy = null)
 * @method RequestClient[]    findAll()
 * @method RequestClient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RequestClient::class);
    }

     /**
     * Recherche les RequestClient en fonction de client
     *
     * @param int    $clientId
     *
     * @return RequestClient[]|null
     */
    public function findByClient(int $clientId): ?array
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery('
            SELECT rc
            FROM App\Entity\RequestClient rc
            WHERE rc.client = :clientId
        ')
            ->setParameter('clientId', $clientId);
    
        return $query->getResult();
    }

     /**
     * Recherche les RequestClient en fonction de l'ID du client et du type.
     *
     * @param int    $clientId
     * @param string $type
     *
     * @return RequestClient[]|null
     */
    public function findByType(int $clientId, string $type): ?array
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery('
            SELECT rc.type, rc.tabledata, rc.status, stats.id AS statsRequestId, stats.naming AS statsNaming
            FROM App\Entity\RequestClient rc
            LEFT JOIN rc.statsRequestClient stats
            WHERE rc.client = :clientId AND rc.type LIKE :type
        ')
        ->setParameter('clientId', $clientId)
        ->setParameter('type', $type . '%');
    
        return $query->getResult();
        
        // return $this->createQueryBuilder('rc')
        // ->leftJoin('rc.statsRequestClient', 'stats')
        // ->andWhere('rc.client = :clientId')
        // ->andWhere($this->getEntityManager()->getExpressionBuilder()->like('rc.type', ':type'))
        // ->setParameter('clientId', $clientId)
        // ->setParameter('type', $type . '%')
        // ->getQuery()
        // ->getResult();
    }

//    /**
//     * @return RequestClient[] Returns an array of RequestClient objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RequestClient
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
