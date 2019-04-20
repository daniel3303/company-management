<?php

namespace App\DataFixtures;

use App\Entity\Company\Company;
use App\Entity\Company\ManagedCompany;
use App\Entity\Invoice\Invoice;
use App\Entity\Invoice\Item;
use App\Entity\Product\Product;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ItemFixtures extends BaseFixture implements DependentFixtureInterface
{

    protected function loadData(ObjectManager $manager) {
        $this->createMany(Item::class, 8000, function (Item $item, int $count){
            $item->setQuantity($this->faker->randomDigit(1, 10000));
            $item->setInvoice($this->getRandomReference(Invoice::class));
            $item->setProduct($this->getRandomReference(Product::class));
            if($this->faker->boolean === true){
                $item->setWaste($this->faker->randomDigit(1, 100));
            }else{
                $item->setWaste(0);
            }
            $item->setPricePerUnit($this->faker->randomFloat(2, 0.01, 40));
        });
    }

    public function getDependencies() {
        return [
            InvoiceFixtures::class,
            ProductFixtures::class
        ];
    }
}
