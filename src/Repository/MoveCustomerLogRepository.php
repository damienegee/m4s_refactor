<?php

namespace App\Repository;

use App\Entity\MoveCustomerLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MoveCustomerLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method MoveCustomerLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method MoveCustomerLog[]    findAll()
 * @method MoveCustomerLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoveCustomerLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MoveCustomerLog::class);
    }

    // /**
    //  * @return MoveCustomerLog[] Returns an array of MoveCustomerLog objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findByCustomer($customerId) {
        return $this->createQueryBuilder('m')
                ->where('m.customerId = :cus')
                ->setParameter('cus', $customerId)
                ->orderBy('m.whenMoved', 'DESC')
                ->getQuery()
                ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?MoveCustomerLog
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
