<?php

namespace App\Tests;

use App\Repository\PaymentMethodRepository;

class MemberTest extends AbstractApiTest
{
    public function testGetCollection(): void
    {
        /** @var \ApiPlatform\Symfony\Bundle\Test\Response */
        $response = $this->createAuthClient()->request('GET', '/api/members?pagination=false');
        
        $result = json_decode($response->getContent());

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['@id' => '/api/members']);
        $this->assertCount(51, $result->{'hydra:member'});
    }

    public function testPostItem(): void
    {
        $container = static::getContainer();
        $pm = $container->get(PaymentMethodRepository::class)->findOneBy(['name' => "CB"]);
        $dateNow = new \DateTime();

        $this->createAuthClient()->request('POST', '/api/members', ['json' => [
            'firstname' => 'Jotaro',
            'lastname' => 'Kujo',
            'email' => 'jojo@hotmail.jp',
            'amount' => '200',
            'willingToVolunteer' => false,
            'subscribedToNewsletter' => false,
            'paymentMethod' => '/api/payment_methods/' . $pm->getId(),
            'date' => $dateNow->format('Y-m-d H:i:s'),
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['num' => 1051]);
        
        $this->createAuthClient()->request('POST', '/api/members', ['json' => [
            'firstname' => 'Dio',
            'lastname' => 'Brando',
            'email' => 'zawardo@hotmail.jp',
            'amount' => '200',
            'willingToVolunteer' => false,
            'subscribedToNewsletter' => false,
            'paymentMethod' => '/api/payment_methods/' . $pm->getId(),
            'date' => $dateNow->format('Y-m-d H:i:s'),
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['num' => 1052]);
    }

    public function testPostItemWithoutAmount(): void
    {
        $dateNow = new \DateTime();

        $this->createAuthClient()->request('POST', '/api/members', ['json' => [
            'firstname' => 'Jotaro',
            'lastname' => 'Kujo',
            'email' => 'jojo@hotmail.jp',
            'amount' => '0',
            'willingToVolunteer' => false,
            'subscribedToNewsletter' => false,
            'date' => $dateNow->format('Y-m-d H:i:s'),
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['num' => 1051]);
    }

    public function testPostItemWithoutPaymentMethodShouldFail(): void
    {
        $container = static::getContainer();
        $pm = $container->get(PaymentMethodRepository::class)->findOneBy(['name' => "CB"]);
        $dateNow = new \DateTime();

        $this->createAuthClient()->request('POST', '/api/members', ['json' => [
            'firstname' => 'Jotaro',
            'lastname' => 'Kujo',
            'email' => 'jojo@hotmail.jp',
            'amount' => '200',
            'willingToVolunteer' => false,
            'subscribedToNewsletter' => false,
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

        $this->createAuthClient()->request('POST', '/api/members', ['json' => [
            'firstname' => 'Jotaro',
            'lastname' => 'Kujo',
            'email' => 'jojo@hotmail.jp',
            'amount' => '0',
            'willingToVolunteer' => false,
            'subscribedToNewsletter' => false,
            'paymentMethod' => '/api/payment_methods/' . $pm->getId(),
            'date' => $dateNow->format('Y-m-d H:i:s'),
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains(['hydra:description' => 'paymentMethod: Vous ne pouvez pas saisir un moyen de paiement sans cotisation']);
    }

}
