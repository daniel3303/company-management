<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Invoice\Invoice;
use App\Entity\Invoice\Item;
use App\Entity\Company\ManagedCompany;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class UserFixtures extends BaseFixture {
    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function loadData(ObjectManager $manager){
        $this->createMany(User::class, 1, function (User $user, int $count){
            $user->setEmail("demo@skern.pt");
            $user->setName("Skern");
            $user->setPlainPassword("123456");
        });
    }
}
