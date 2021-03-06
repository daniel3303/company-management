<?php

namespace App\Repository\Company;

use App\Entity\Company\Company;
use App\Entity\User;
use App\Repository\BaseRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Company|null         find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null         findOneBy(array $criteria, array $orderBy = null)
 * @method Company[]            findAll()
 * @method Company[]            findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Company[]|Paginator  findAllWithPaginator(?string $orderProperty = null, ?string $order = 'asc', ?array $allowedSortProperties = null)
 */
class CompanyRepository extends BaseRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Company::class);
    }

    public function countAllCompanies(): int {
        try {
            $count = $this->createQueryBuilder('c')
                ->select('COUNT(c)')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
            return 0;
        } catch (NoResultException $e) {
            return 0;
        }
        return $count ?? 0;
    }

    /**
     * @param Company $company
     * @return Paginator|Company[]
     */
    public function findClientsOf(Company $issuer): Paginator {
        return new Paginator(
            $this->createQueryBuilder('client')
            ->select('client')
            ->innerJoin('client.receivedInvoices', 'receivedInvoices')
            ->where('receivedInvoices.issuer = :issuer')->setParameter('issuer', $issuer)->getQuery()
        );
    }
}