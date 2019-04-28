<?php

namespace App\Repository\Company;

use App\Entity\Company\ManagedCompany;
use App\Repository\BaseRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ManagedCompany|null find($id, $lockMode = null, $lockVersion = null)
 * @method ManagedCompany|null findOneBy(array $criteria, array $orderBy = null)
 * @method ManagedCompany[]    findAll()
 * @method ManagedCompany[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method ManagedCompany[]|Paginator  findAllWithPaginator(?string $property = null, ?string $order = 'asc', ?array $allowedSortProperties = null)
 */
class ManagedCompanyRepository extends BaseRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ManagedCompany::class);
    }
}
