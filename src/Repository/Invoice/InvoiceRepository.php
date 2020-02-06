<?php

namespace App\Repository\Invoice;

use App\Entity\Invoice\Invoice;
use App\Repository\BaseRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Invoice|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invoice[]    findAll()
 * @method Invoice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Invoice[]|Paginator  findAllWithPaginator(?string $orderProperty = null, ?string $order = 'asc', ?array $allowedSortProperties = null)
 */
class InvoiceRepository extends BaseRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Invoice::class);
    }



    public function countAllInvoices() : int {
        try {
            $count = $this->createQueryBuilder("i")
                ->select("COUNT(i)")
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
            return 0;
        } catch (NoResultException $e) {
            return 0;
        }
        return $count ?? 0;
    }
}
