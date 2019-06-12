<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-04-28
 * Time: 01:43
 */

namespace App\Repository;


use App\Doctrine\Filter\FilterInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

abstract class BaseRepository extends ServiceEntityRepository{
    public function __construct(RegistryInterface $registry, $entityClass) {
        parent::__construct($registry, $entityClass);
    }


    public function findAllWithPaginator(?string $orderProperty = null, ?string $order = 'asc', ?array $allowedSortProperties = null, ?FilterInterface $filter = null): Paginator {
        $validProperty = false;

        //Check the order is valid. If not assigns asc by default
        if(in_array(strtolower($order), ["asc", "desc"]) === false){
            $order = "asc";
        }

        //Check if the order property exists and is allowed
        $metadata = $this->getClassMetadata();
        if($metadata->hasField($orderProperty) === true
            && ($allowedSortProperties === null || $allowedSortProperties !== null && in_array($orderProperty, $allowedSortProperties) === true)){
            $validProperty = true;
        }

        $query = $this->createQueryBuilder("o")->select("o");

        // if a filter or filter collection is provided
        if($filter !== null){
            $filter->filterQuery($query, "o");
        }

        // set the ordering
        if($validProperty){
            $query = $query->orderBy("o.".$orderProperty, $order);
        }

        return new Paginator($query);
    }
}