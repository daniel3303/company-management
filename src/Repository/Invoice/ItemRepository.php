<?php

namespace App\Repository\Invoice;

use App\Entity\Invoice\Invoice;
use App\Entity\Invoice\Item;
use App\Repository\BaseRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Item[]|Paginator  findAllWithPaginator(?string $property = null, ?string $order = 'asc', ?array $allowedSortProperties = null)
 */
class ItemRepository extends BaseRepository {
    public function __construct(RegistryInterface $registry) {
        parent::__construct($registry, Item::class);
    }
}
