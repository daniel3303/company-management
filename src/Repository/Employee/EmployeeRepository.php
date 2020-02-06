<?php

namespace App\Repository\Employee;

use App\Entity\Employee\Employee;
use App\Repository\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends BaseRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Employee::class);
    }

}
