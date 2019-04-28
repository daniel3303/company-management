<?php

namespace App\Repository\Product;

use App\Entity\Product\Product;
use App\Repository\BaseRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Product[]|Paginator  findAllWithPaginator(?string $property = null, ?string $order = 'asc', ?array $allowedSortProperties = null)
 */
class ProductRepository extends BaseRepository {
    public function __construct(RegistryInterface $registry) {
        parent::__construct($registry, Product::class);
    }

    public function countAllProducts() : int {
        try {
            $count = $this->createQueryBuilder("p")
                ->select("COUNT(p)")
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
            return 0;
        }
        return $count === null ? 0 : $count;
    }

}
