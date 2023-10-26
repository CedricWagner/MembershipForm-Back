<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Repository\UserRepository;

abstract class AbstractApiTest extends ApiTestCase
{
    public function setUp(): void
    {
        self::bootKernel();
    }

    /**
     * Create a client with a default Authorization header.
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function createAuthClient()
    {
        $client = self::createClient();
        $container = static::getContainer();
        $user = $container->get(UserRepository::class)->findOneBy(['email' => "admin@localhost.test"]);
        $client->loginUser($user);
        return $client;
    }
}
