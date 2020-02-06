<?php

namespace App\Repository\Company;

use App\Entity\Company\ManagedCompany;
use App\Repository\BaseRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ManagedCompany|null find($id, $lockMode = null, $lockVersion = null)
 * @method ManagedCompany|null findOneBy(array $criteria, array $orderBy = null)
 * @method ManagedCompany[]    findAll()
 * @method ManagedCompany[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method ManagedCompany[]|Paginator  findAllWithPaginator(?string $orderProperty = null, ?string $order = 'asc', ?array $allowedSortProperties = null)
 */
class ManagedCompanyRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ManagedCompany::class);
    }
}
