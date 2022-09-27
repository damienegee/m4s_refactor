<?php

namespace App\Repository;

use App\Entity\ReturnedLoan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReturnedLoan|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReturnedLoan|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReturnedLoan[]    findAll()
 * @method ReturnedLoan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReturnedLoanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReturnedLoan::class);
    }

    // /**
    //  * @return ReturnedLoan[] Returns an array of ReturnedLoan objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReturnedLoan
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
