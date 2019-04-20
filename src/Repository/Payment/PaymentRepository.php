<?php

namespace App\Repository\Payment;

use App\Entity\Invoice\Invoice;
use App\Entity\Payment\Payment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Payment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Payment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Payment[]    findAll()
 * @method Payment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentRepository extends ServiceEntityRepository {
    public function __construct(RegistryInterface $registry) {
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
        }
        return $count === null ? 0 : $count;
    }

    /**
     * @param string $property
     * @param string $order
     * @return Payment[]|Paginator
     */
    public function findAllOrderedBy(string $property = "id", string $order = "asc") : Paginator{
        return new Paginator($this->createQueryBuilder("p")
            ->select("p")
            ->orderBy("p.".$property, $order));
    }
}
