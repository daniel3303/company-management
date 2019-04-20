<?php

namespace App\DataFixtures;

use App\Entity\Company\Company;
use App\Entity\Company\ManagedCompany;
use App\Entity\Invoice\Invoice;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class InvoiceFixtures extends BaseFixture implements DependentFixtureInterface
{

    protected function loadData(ObjectManager $manager) {
        $this->createMany(Invoice::class, 1500, function (Invoice $invoice, int $count){
            if($this->faker->boolean === true){
                $invoice->setClient($this->getRandomReference(Company::class));
                $invoice->setIssuer($this->getRandomReference(ManagedCompany::class));
            }else{
                $invoice->setClient($this->getRandomReference(ManagedCompany::class));
                $invoice->setIssuer($this->getRandomReference(Company::class));
            }
            $invoice->setDate(new \DateTime("now"));
            $invoice->setNumber($this->faker->unique()->numberBetween(1, 10000));
        });
    }

    public function getDependencies() {
        return [
            CompanyFixtures::class
        ];
    }
}
