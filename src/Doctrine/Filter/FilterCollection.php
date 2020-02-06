<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-06-10
 * Time: 04:50
 */

namespace App\Doctrine\Filter;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class FilterCollection implements FilterInterface {
    /**
     * @var Filter[]|\ArrayObject
     */
    private $filters;

    public function __construct() {
        $this->filters = new \ArrayObject();
    }

    public function addFilter(Filter $filter){
        $this->filters->append($filter);
    }

    public function filterQuery(QueryBuilder $queryBuilder, string $rootAlias, string $prefix = ''){
        $index = 0;

        foreach ($this->filters as $filter){
            $filter->filterQuery($queryBuilder, $rootAlias, (string) $index);

            ++$index;
        }
    }

}