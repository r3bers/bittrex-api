<?php

declare(strict_types=1);

namespace R3bers\BittrexApi\Tests;

use PHPUnit\Framework\TestCase;
use R3bers\BittrexApi\Api\Account;
use R3bers\BittrexApi\Api\Market;
use R3bers\BittrexApi\Api\PublicApi;
use R3bers\BittrexApi\BittrexClient;
use R3bers\BittrexApi\Exception\InvalidCredentialException;

class BittrexClientTest extends TestCase
{
    public function testIsEmptyInitialCredential()
    {
        $client = new BittrexClient();

        $this->assertEmpty($client->getKey());
        $this->assertEmpty($client->getSecret());
    }

    public function testSetCredential()
    {
        $secret = 'API_SECRET';
        $key = 'API_KEY';

        $client = new BittrexClient();
        $client->setCredential($key, $secret);

        $this->assertEquals($client->getKey(), $key);
        $this->assertEquals($client->getSecret(), $secret);
    }

    public function testPublic()
    {
        $client = new BittrexClient();

        $this->assertInstanceOf(PublicApi::class, $client->public());
    }

    public function testMarket()
    {
        $client = new BittrexClient();
        $client->setCredential('API_KEY', 'API_SECRET');

        $this->assertInstanceOf(Market::class, $client->market());
    }

    public function testMarketThrowInvalidCredentialException()
    {
        $client = new BittrexClient();

        $this->expectException(InvalidCredentialException::class);

        $client->market();
    }

    public function testAccount()
    {
        $client = new BittrexClient();
        $client->setCredential('API_KEY', 'API_SECRET');

        $this->assertInstanceOf(Account::class, $client->account());
    }

    public function testAccountThrowInvalidCredentialException()
    {
        $client = new BittrexClient();

        $this->expectException(InvalidCredentialException::class);

        $client->account();
    }
}
