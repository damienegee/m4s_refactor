<?php

namespace App\Repository;

use App\Entity\Scripting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Scripting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Scripting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Scripting[]    findAll()
 * @method Scripting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScriptingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Scripting::class);
    }

    // /**
    //  * @return Scripting[] Returns an array of Scripting objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findByName($value)
    {
        return $this->createQueryBuilder('s')
            ->where('s.name like :val')
            ->setParameter('val', '%' . $value . '%')
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Scripting
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
