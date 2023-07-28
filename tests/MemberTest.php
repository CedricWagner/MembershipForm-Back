<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Repository\PaymentMethodRepository;

class MemberTest extends ApiTestCase
{
    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testGetCollection(): void
    {
        $response = static::createClient()->request('GET', '/api/members');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['@id' => '/api/members']);
        $this->assertJsonContains(['hydra:totalItems' => 21]);
    }

    public function testPostItem(): void
    {
        $container = static::getContainer();
        $pm = $container->get(PaymentMethodRepository::class)->findOneBy(['name' => "CB"]);
        $dateNow = new \DateTime();

        $response = static::createClient()->request('POST', '/api/members', ['json' => [
            'firstname' => 'Jotaro',
            'lastname' => 'Kujo',
            'email' => 'jojo@hotmail.jp',
            'amount' => '200',
            'willingToVolunteer' => false,
            'paymentMethod' => '/api/payment_methods/' . $pm->getId(),
            'date' => $dateNow->format('Y-m-d H:i:s'),
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['num' => 22]);
    }
}
