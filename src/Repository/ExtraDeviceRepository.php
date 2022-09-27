<?php

namespace App\Repository;

use App\Entity\ExtraDevice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExtraDevice|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExtraDevice|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExtraDevice[]    findAll()
 * @method ExtraDevice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExtraDeviceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExtraDevice::class);
    }

    // /**
    //  * @return ExtraDevice[] Returns an array of ExtraDevice objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findByLocationId($locationId){
        return $this->createQueryBuilder('e')
                ->where('e.m4sSchoollocationId = :locid')
                ->setParameter('locid', $locationId)
                ->getQuery()
                ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?ExtraDevice
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
