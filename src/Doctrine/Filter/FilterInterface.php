<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-06-10
 * Time: 05:18
 */

namespace App\Doctrine\Filter;


use Doctrine\ORM\QueryBuilder;

interface FilterInterface {
    public function filterQuery(QueryBuilder $queryBuilder, string $rootAlias, string $prefix = "");
}