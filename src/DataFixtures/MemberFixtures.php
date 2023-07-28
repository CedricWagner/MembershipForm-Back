<?php

namespace App\DataFixtures;

use App\Entity\Member;
use App\Entity\PaymentMethod;
use App\Repository\PaymentMethodRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class MemberFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var PaymentMethodRepository */
        $pmRepository = $manager->getRepository(PaymentMethod::class);
        $pms = $pmRepository->findAll(); 

        // create first member
        $member = new Member();
        $member->setLastname('Wagner');
        $member->setFirstname('Cédric');
        $member->setAmount(5);
        $member->setDate(new DateTime());
        $member->setWillingToVolunteer(true);
        $member->setNum(1);
        $member->setEmail('test@cedricwagner.fr');
        $member->setPaymentMethod($pms[0]);

        $manager->persist($member);

        // create other random members
        $faker = Faker\Factory::create('fr_FR');
        $currentNum = $member->getNum();
        $members = [];
        for ($i = 0; $i < 20; $i++) {
            $currentNum++;
            $member = new Member();
            $member->setLastname($faker->lastName);
            $member->setFirstname($faker->firstName);
            $member->setAmount(random_int(0, 5));
            $member->setDate(new DateTime());
            $member->setWillingToVolunteer((bool)random_int(0, 1));
            $member->setNum($currentNum);
            $member->setEmail($faker->email);
            if ($member->getAmount() > 0) {
                $member->setPaymentMethod($pms[random_int(0,3)]);
            }

            $members[$i] = $member;

            $manager->persist($members[$i]);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            PaymentMethodFixtures::class,
        ];
    }
}
