<?php

namespace App\DataFixtures;

use App\Service\PaymentMethodFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PaymentMethodFixtures extends Fixture
{
    public function __construct(protected PaymentMethodFactory $pmFactory)
    {
        
    }

    public function load(ObjectManager $manager): void
    {
        $pm = $this->pmFactory->createFromName('CB');
        $manager->persist($pm);

        $pm = $this->pmFactory->createFromName('Espèce');
        $manager->persist($pm);

        $pm = $this->pmFactory->createFromName('Stück');
        $manager->persist($pm);

        $pm = $this->pmFactory->createFromName('CB Stück');
        $manager->persist($pm);

        $manager->flush();
    }
}
