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

class AppFixtures extends Fixture {
    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager){
        // User
        $user = new User();
        $user->setEmail("demo@skern.pt");
        $user->setName("Skern");

        $user->setPlainPassword("123456");
        $manager->persist($user);
        $manager->flush();
    }
}
