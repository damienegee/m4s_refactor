<?php

namespace App\Repository;

use App\Entity\Institution;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Institution|null find($id, $lockMode = null, $lockVersion = null)
 * @method Institution|null findOneBy(array $criteria, array $orderBy = null)
 * @method Institution[]    findAll()
 * @method Institution[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstitutionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Institution::class);
    }

    // /**
    //  * @return Institution[] Returns an array of Institution objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Institution
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findInstitutionBySynergy($syergyId): ? Institution {
        return $this->createQueryBuilder('i')
                ->where('i.synergy_id = :synergy')
                ->setParameter('synergy', $syergyId)
                ->getQuery()
                ->getOneOrNullResult();
    }

    public function findInstitutionByName($name) {
        return $this->createQueryBuilder('i')
                ->where('i.institution_name like :name')
                ->setParameter('name', '%'.$name.'%')
                ->getQuery()
                ->execute();
    }

    public function findInstitutionByExactName($name): ? Institution {
        return $this->createQueryBuilder('i')
                ->where('i.institution_name = :name')
                ->setParameter('name', $name)
                ->getQuery()
                ->getOneOrNullResult();
    }
}
