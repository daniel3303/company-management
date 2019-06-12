<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-06-10
 * Time: 04:51
 */

namespace App\Doctrine\Filter;


use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class Filter implements FilterInterface {
    const EQUAL_TO = "=";
    const GREATER_THAN = ">";
    const GREATER_OR_EQUAL_TO = ">=";
    const LESS_THAN = "<";
    const LESS_OR_EQUAL_TO = "<=";
    const CONTAINS = "LIKE";
    const NOT_EQUAL_TO = "!=";

    /**
     * @var ?string $name
     */
    private $name;

    /**
     * @var string $path
     */
    private $path;

    /**
     * @var string $comparator
     */
    private $comparator;

    /**
     * @var mixed
     */
    private $value;

    public function __construct(string $path, string $comparator, $value) {
        $this->setPath($path);
        if($this->isValidComparator($comparator)){
            $this->setComparator($comparator);
        }else{
            // default comparator
            $this->setComparator(self::EQUAL_TO);
        }
        $this->setValue($value);
    }

    public function isValidComparator($comparator){
        return in_array($comparator, [
            self::EQUAL_TO,
            self::GREATER_THAN,
            self::GREATER_OR_EQUAL_TO,
            self::LESS_THAN,
            self::LESS_OR_EQUAL_TO,
            self::CONTAINS,
            self::NOT_EQUAL_TO,
        ]);
    }

    /**
     * @return string
     */
    public function getPath(): string {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): self {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getComparator(): string {
        return $this->comparator;
    }

    /**
     * @param string $comparator
     */
    public function setComparator(string $comparator): self {
        $this->comparator = $comparator;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue() {
        if($this->getComparator() === self::CONTAINS){
            return "%".$this->value."%";
        }

        return $this->value;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string {
        return $this->name;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $alias
     * @return false|int|string
     */
    public function getEntityForAlias(QueryBuilder $queryBuilder, string $alias) : ?string {
        $index = array_search($alias, $queryBuilder->getRootAliases());

        if($index === false){
            return null;
        }

        return $queryBuilder->getRootEntities()[$index];

    }

    /**
     * @param mixed $name
     * @return self
     */
    public function setName(?string $name): self {
        $this->name = $name;
        return $this;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value): self {
        $this->value = $value;

        return $this;
    }


    public function filterQuery(QueryBuilder $queryBuilder, string $rootAlias, string $prefix = ""){
        $entityManager = $queryBuilder->getEntityManager();
        $rootEntity = $this->getEntityForAlias($queryBuilder, $rootAlias);

        if($rootEntity === null){
            // ignore the filter if no entity is found
            return;
        }

        $comparator = $this->getComparator();
        $path = explode(".", $this->getPath());
        $index = 0;

        $prevAlias = $rootAlias;
        $metadata = $entityManager->getClassMetadata($rootEntity);

        // walks through path
        foreach ($path as $property){
            $isLast = end($path) === $property;

            if($metadata->hasAssociation($property)){
                if($isLast){
                    $queryBuilder->andWhere("$prevAlias.$property $comparator :param_$prefix".$index);
                    $queryBuilder->setParameter(":param_$prefix".$index, $this->getValue());
                }else{
                    $newAlias = "obj_".$prefix.++$index;
                    $queryBuilder->join("$prevAlias.$property", $newAlias);
                    $assocClass = $metadata->getAssociationTargetClass($property);
                    $metadata = $entityManager->getClassMetadata($assocClass);
                    $prevAlias = $newAlias;
                }
            }else if($metadata->hasField($property)){
                ++$index;
                $queryBuilder->andWhere("$prevAlias.$property $comparator :param_$prefix".$index);
                $queryBuilder->setParameter(":param_$prefix".$index, $this->getValue());
            }
        }

    }

}