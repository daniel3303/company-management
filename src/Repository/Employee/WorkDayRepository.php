<?php

namespace App\Repository\Employee;

use App\Entity\Employee\WorkDay;
use App\Repository\BaseRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WorkDay|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkDay|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkDay[]    findAll()
 * @method WorkDay[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkDayRepository extends BaseRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, WorkDay::class);
    }

    public function findAllBetween(?\DateTime $start, \DateTime $end): Paginator {
        $queryBuilder = $this->createQueryBuilder('wd')->select('wd');

        if($start !== null){
            $queryBuilder->andWhere('wd.day >= :start')->setParameter(':start', $start);
        }

        if($end !== null){
            $queryBuilder->andWhere('wd.day <= :end')->setParameter(':end', $end);
        }

        $queryBuilder->orderBy('wd.day', 'desc');

        return new Paginator($queryBuilder);
    }

}
