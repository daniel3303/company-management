<?php

namespace App\Repository\Payment;

use App\Entity\Payment\Payment;
use App\Repository\BaseRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Payment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Payment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Payment[]    findAll()
 * @method Payment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Payment[]|Paginator  findAllWithPaginator(?string $orderProperty = null, ?string $order = 'asc', ?array $allowedSortProperties = null)
 */
class PaymentRepository extends BaseRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Payment::class);
    }

    public function countAllPayments() : int {
        try {
            $count = $this->createQueryBuilder("p")
                ->select("COUNT(p)")
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
