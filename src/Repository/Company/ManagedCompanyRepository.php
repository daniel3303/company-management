<?php

namespace App\Repository\Company;

use App\Entity\Company\ManagedCompany;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ManagedCompany|null find($id, $lockMode = null, $lockVersion = null)
 * @method ManagedCompany|null findOneBy(array $criteria, array $orderBy = null)
 * @method ManagedCompany[]    findAll()
 * @method ManagedCompany[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ManagedCompanyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ManagedCompany::class);
    }

    // /**
    //  * @return ManagedCompany[] Returns an array of ManagedCompany objects
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

    /*
    public function findOneBySomeField($value): ?ManagedCompany
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
