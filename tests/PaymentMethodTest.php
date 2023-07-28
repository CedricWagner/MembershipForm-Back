<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class PaymentMethodTest extends ApiTestCase
{
    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testGetCollection(): void
    {
        $response = static::createClient()->request('GET', '/api/payment_methods');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['@id' => '/api/payment_methods']);
        $this->assertJsonContains(['hydra:totalItems' => 4]);
    }
}
