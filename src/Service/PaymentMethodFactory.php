<?php

namespace App\Service;

use App\Entity\Admin;
use App\Entity\PaymentMethod;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PaymentMethodFactory
{
    public function __construct(private EntityManagerInterface $entityManager, private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function createFromName(string $name): PaymentMethod
    {
        $pm = new PaymentMethod();
        $pm->setName($name);

        $this->entityManager->persist($pm);
        $this->entityManager->flush();

        return $pm;
    }
}
