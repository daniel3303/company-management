<?php

namespace App\DataFixtures;

use App\Entity\Company\Company;
use App\Entity\Company\ManagedCompany;
use App\Entity\Invoice\Invoice;
use App\Entity\Invoice\Item;
use App\Entity\Payment\Payment;
use App\Entity\Product\Product;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class PaymentFixtures extends BaseFixture implements DependentFixtureInterface
{

    protected function loadData(ObjectManager $manager) {
        $this->createMany(Payment::class, 2000, function (Payment $payment, int $count){
            $payment->setInvoice($this->getRandomReference(Invoice::class));
            $payment->setDescription($this->faker->realText());
            $payment->setDate(new \DateTime("now"));
            $payment->setMethod("Cheque");
            $payment->setAmount($this->faker->randomFloat(2, 1, 1000));
        });
    }

    public function getDependencies() {
        return [
            InvoiceFixtures::class,
            ProductFixtures::class
        ];
    }
}
