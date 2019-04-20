<?php

namespace App\Entity\Company;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Company\CompanyRepository")
 * @Serializer\AccessType("public_method")
 * @Serializer\ReadOnly()
 */
class ManagedCompany extends Company {
    /**
     * @Groups({"entity_only"})
     */
    public function getType() : string {
        return self::MANAGED_COMPANY;
    }
}
