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
    private const API_KEY = '6d7f896ea1b0ff47559473c91cdbe318';
    private const API_SECRET = '6d7f896ea1b0ff47559473c91cdbe318';

    public function testSetCredential()
    {
        $client = new BittrexClient();

        $this->expectException(InvalidCredentialException::class);

        $client->setCredential('Not MD5', 'Also not MD5');
    }

    public function testSetCredentialThrowInvalidCredentialException()
    {
        $client = new BittrexClient();
        $client->setCredential(self::API_KEY, self::API_SECRET);

        $this->assertEquals(true, $client->haveValidCredentials());
    }

    public function testPublic()
    {
        $client = new BittrexClient();

        $this->assertInstanceOf(PublicApi::class, $client->public());
    }

    public function testMarket()
    {
        $client = new BittrexClient();
        $client->setCredential(self::API_KEY, self::API_SECRET);

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
        $client->setCredential(self::API_KEY, self::API_SECRET);

        $this->assertInstanceOf(Account::class, $client->account());
    }

    public function testAccountThrowInvalidCredentialException()
    {
        $client = new BittrexClient();

        $this->expectException(InvalidCredentialException::class);

        $client->account();
    }
}