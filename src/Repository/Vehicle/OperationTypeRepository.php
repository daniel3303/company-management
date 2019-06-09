<?php

namespace App\Repository\Vehicle;

use App\Entity\Vehicle\OperationType;
use App\Repository\BaseRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method OperationType|null find($id, $lockMode = null, $lockVersion = null)
 * @method OperationType|null findOneBy(array $criteria, array $orderBy = null)
 * @method OperationType[]    findAll()
 * @method OperationType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OperationTypeRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OperationType::class);
    }

    // /**
    //  * @return OperationType[] Returns an array of OperationType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OperationType
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
