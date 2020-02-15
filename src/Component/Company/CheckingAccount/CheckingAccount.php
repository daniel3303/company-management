<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 15/02/2020
 * Time: 20:49
 */

namespace App\Component\Company\CheckingAccount;


use App\Entity\Company\Company;
use Doctrine\Common\Collections\ArrayCollection;

class CheckingAccount {
    /**
     * @var Company
     */
    private Company $issuer;

    /**
     * @var Company
     */
    private Company $client;

    protected ArrayCollection $checkingAccountItems;

    public function __construct(Company $issuer, Company $client) {
        $this->issuer = $issuer;
        $this->client = $client;
        $this->checkingAccountItems = new ArrayCollection();
    }

    /**
     * @return Company
     */
    public function getIssuer(): Company {
        return $this->issuer;
    }

    /**
     * @param Company $issuer
     */
    public function setIssuer(Company $issuer): void {
        $this->issuer = $issuer;
    }

    /**
     * @return Company
     */
    public function getClient(): Company {
        return $this->client;
    }

    /**
     * @param Company $client
     */
    public function setClient(Company $client): void {
        $this->client = $client;
    }

    /**
     * @return ArrayCollection|CheckingAccountItem[]
     */
    public function getItems(): ArrayCollection {
        return $this->checkingAccountItems;
    }

    public function addItem(CheckingAccountItem $item): self {
        if(!$this->checkingAccountItems->contains($item)){
            $this->checkingAccountItems->add($item);
        }
        return $this;
    }

}