<?php

namespace App\DataFixtures;

use App\Entity\Company\Company;
use App\Entity\Company\ManagedCompany;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CompanyFixtures extends BaseFixture implements DependentFixtureInterface
{

    protected function loadData(ObjectManager $manager) {
        $this->createMany(ManagedCompany::class, 4, function (ManagedCompany $company, int $count){
            $company->setUser($this->getRandomReference(User::class));
            $company->setName($this->faker->unique()->company);
            $company->setAddress($this->faker->unique()->address);
            $company->setNif($this->faker->unique()->numberBetween(100000000, 999999999));
        });

        $this->createMany(Company::class, 900, function (Company $company, int $count){
           $company->setUser($this->getRandomReference(User::class));
           $company->setName($this->faker->unique()->company);
           $company->setAddress($this->faker->unique()->address);
           $company->setNif($this->faker->unique()->numberBetween(100000000, 999999999));
        });
    }

    public function getDependencies() {
        return [
            UserFixtures::class
        ];
    }
}
