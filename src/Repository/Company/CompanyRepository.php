<?php

namespace App\Repository\Company;

use App\Entity\Company\Company;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneBy(array $criteria, array $orderBy = null)
 * @method Company[]    findAll()
 * @method Company[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRepository extends ServiceEntityRepository {
    public function __construct(RegistryInterface $registry) {
        parent::__construct($registry, Company::class);
    }

    /**
     * @param string $property
     * @param string $order
     * @return Company[]|Paginator
     */
    public function findAllOrderedBy(string $property = "id", string $order = "asc") : Paginator{
        return new Paginator($this->createQueryBuilder("c")
            ->select("c")
            ->orderBy("c.".$property, $order));
    }

    public function findAllOrderedByNumberInvoices(){
        return new Paginator($this->createQueryBuilder('c')
        ->select('c, (COUNT(ii) + COUNT(ri)) as HIDDEN totalInvoices')->leftJoin('c.issuedInvoices', ' ii')
        ->leftJoin('c.receivedInvoices','ri')->orderBy('totalInvoices', 'ASC'));
    }

    public function countAllCompanies(User $user) : int {
        try {
            $count = $this->createQueryBuilder("c")
                ->select("COUNT(c)")
                ->where("c.user = :user")
                ->setParameter("user", $user)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
            return 0;
        }
        return $count === null ? 0 : $count;
    }
}
