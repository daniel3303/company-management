<?php

namespace App\Repository\Employee;

use App\Entity\Employee\WorkInterval;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WorkInterval|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkInterval|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkInterval[]    findAll()
 * @method WorkInterval[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkIntervalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkInterval::class);
    }

    // /**
    //  * @return WorkInterval[] Returns an array of WorkInterval objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WorkInterval
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
