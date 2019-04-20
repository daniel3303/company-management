<?php

namespace App\DataFixtures;

use App\Entity\Company\Company;
use App\Entity\Company\ManagedCompany;
use App\Entity\Invoice\Invoice;
use App\Entity\Product\Product;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ProductFixtures extends BaseFixture implements DependentFixtureInterface
{

    protected function loadData(ObjectManager $manager) {
        $this->createMany(Product::class, 30, function (Product $product, int $count){
            $product->setName($this->faker->unique()->word);
            $product->setDescription($this->faker->text(200));
            $product->setUnit("kg");
        });
    }

    public function getDependencies() {
        return [
            InvoiceFixtures::class
        ];
    }
}
