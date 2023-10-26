<?php

namespace App\Tests;

class PaymentMethodTest extends AbstractApiTest
{
    public function testGetCollection(): void
    {
        $this->createAuthClient()->request('GET', '/api/payment_methods');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['@id' => '/api/payment_methods']);
        $this->assertJsonContains(['hydra:totalItems' => 4]);
    }
}
