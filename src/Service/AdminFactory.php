<?php

namespace App\Service;

use App\Entity\Admin;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminFactory
{
    public function __construct(private EntityManagerInterface $entityManager, private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function createFromEmailAndRawPassword(string $email, string $rawPassword): User
    {
        $admin = new User();
        $admin->setEmail($email);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, $rawPassword));

        $this->entityManager->persist($admin);
        $this->entityManager->flush();

        return $admin;
    }
}
