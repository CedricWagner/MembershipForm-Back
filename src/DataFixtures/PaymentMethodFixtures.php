<?php

namespace App\DataFixtures;

use App\Entity\PaymentMethod;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PaymentMethodFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $pm = new PaymentMethod();
        $pm->setName("CB");
        $manager->persist($pm);

        $pm = new PaymentMethod();
        $pm->setName("Espèce");
        $manager->persist($pm);

        $pm = new PaymentMethod();
        $pm->setName("Stück");
        $manager->persist($pm);

        $pm = new PaymentMethod();
        $pm->setName("CB Stück");
        $manager->persist($pm);

        $manager->flush();
    }
}
