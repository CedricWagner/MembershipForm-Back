<?php

namespace App\DataFixtures;

use App\Service\AdminFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function __construct(protected AdminFactory $adminFactory)
    {
        
    }

    public function load(ObjectManager $manager): void
    {
        $this->adminFactory->createFromEmailAndRawPassword('admin@localhost.test', 'admin');
    }
}
