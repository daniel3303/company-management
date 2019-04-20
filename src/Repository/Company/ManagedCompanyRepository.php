<?php

namespace App\Repository\Company;

use App\Entity\Company\ManagedCompany;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
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

    /**
     * @param string $property
     * @param string $order
     * @return ManagedCompany[]|Paginator
     */
    public function findAllOrderedBy(string $property = "id", string $order = "asc") : Paginator{
        return new Paginator($this->createQueryBuilder("c")
            ->select("c")
            ->orderBy("c.".$property, $order));
    }
}
