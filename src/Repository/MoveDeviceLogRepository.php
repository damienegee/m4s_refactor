<?php

namespace App\Repository;

use App\Entity\MoveDeviceLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MoveDeviceLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method MoveDeviceLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method MoveDeviceLog[]    findAll()
 * @method MoveDeviceLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoveDeviceLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MoveDeviceLog::class);
    }

    // /**
    //  * @return MoveDeviceLog[] Returns an array of MoveDeviceLog objects
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

    public function findByDevice($deviceId) {
        return $this->createQueryBuilder('m')
                ->where('m.deviceId = :dev')
                ->setParameter('dev', $deviceId)
                ->orderBy('m.whenMoved', 'DESC')
                ->getQuery()
                ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?MoveDeviceLog
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
