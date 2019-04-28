<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-04-28
 * Time: 01:43
 */

namespace App\Repository;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

abstract class BaseRepository extends ServiceEntityRepository{
    public function __construct(RegistryInterface $registry, $entityClass) {
        parent::__construct($registry, $entityClass);
    }


    public function findAllWithPaginator(?string $property = null, ?string $order = 'asc', ?array $allowedSortProperties = null): Paginator {
        $validProperty = false;

        //Check the order is valid. If not assigns asc by default
        if(in_array(strtolower($order), ["asc", "desc"]) === false){
            $order = "asc";
        }

        //Check if the order property exists and is allowed
        $metadata = $this->getClassMetadata();
        if($metadata->hasField($property) === true
            && ($allowedSortProperties === null || $allowedSortProperties !== null && in_array($property, $allowedSortProperties) === true)){
            $validProperty = true;
        }

        $query = $this->createQueryBuilder("o")->select("o");
        if($validProperty){
            $query = $query->orderBy("o.".$property, $order);
        }
        return new Paginator($query);
    }
}