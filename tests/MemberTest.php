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

    public function testPostItemWithoutAmount(): void
    {
        $container = static::getContainer();
        $pm = $container->get(PaymentMethodRepository::class)->findOneBy(['name' => "CB"]);
        $dateNow = new \DateTime();

        $response = static::createClient()->request('POST', '/api/members', ['json' => [
            'firstname' => 'Jotaro',
            'lastname' => 'Kujo',
            'email' => 'jojo@hotmail.jp',
            'amount' => '0',
            'willingToVolunteer' => false,
            'date' => $dateNow->format('Y-m-d H:i:s'),
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['num' => 22]);
    }

    public function testPostItemWithoutPaymentMethodShouldFail(): void
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
            'date' => $dateNow->format('Y-m-d H:i:s'),
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains(['hydra:description' => 'paymentMethod: Vous devez sélectionner un moyen de paiement']);
    }

    public function testPostItemWithPaymentMethodButNoAmountShouldFail(): void
    {
        $container = static::getContainer();
        $pm = $container->get(PaymentMethodRepository::class)->findOneBy(['name' => "CB"]);
        $dateNow = new \DateTime();

        $response = static::createClient()->request('POST', '/api/members', ['json' => [
            'firstname' => 'Jotaro',
            'lastname' => 'Kujo',
            'email' => 'jojo@hotmail.jp',
            'amount' => '0',
            'willingToVolunteer' => false,
            'paymentMethod' => '/api/payment_methods/' . $pm->getId(),
            'date' => $dateNow->format('Y-m-d H:i:s'),
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains(['hydra:description' => 'paymentMethod: Vous ne pouvez pas saisir un moyen de paiement sans cotisation']);
    }
}
