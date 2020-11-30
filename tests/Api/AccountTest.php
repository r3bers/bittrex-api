<?php

declare(strict_types=1);

namespace R3bers\BittrexApi\Tests\Api;

use R3bers\BittrexApi\Api\Account;

class AccountTest extends ApiTestCase
{
    public function testGetVolume()
    {
        $this->createApi()->getVolume();
        $request = $this->getLastRequest();

        $this->assertEquals(
            '/v3/account/volume',
            $request->getUri()->__toString()
        );
        $this->assertEquals(
            'API_KEY',
            $request->getHeaderLine('Api-Key')
        );
        $this->assertEquals(
            hash('sha512', ''),
            $request->getHeaderLine('Api-Content-Hash')
        );
        $this->assertNotEmpty($request->getHeaderLine('Api-Timestamp'));
        $this->assertNotEmpty($request->getHeaderLine('Api-Signature'));
    }


    public function testGetBalances()
    {
        $this->createApi()->getBalances();
        $request = $this->getLastRequest();

        $this->assertEquals(
            '/v3/balances',
            $request->getUri()->__toString()
        );
        $this->assertEquals(
            'API_KEY',
            $request->getHeaderLine('Api-Key')
        );
        $this->assertEquals(
            hash('sha512', ''),
            $request->getHeaderLine('Api-Content-Hash')
        );
        $this->assertNotEmpty($request->getHeaderLine('Api-Timestamp'));
        $this->assertNotEmpty($request->getHeaderLine('Api-Signature'));
    }

    public function testHeadBalances()
    {
        $this->createApi()->headBalances();
        $request = $this->getLastRequest();
        $this->assertEquals('HEAD', $request->getMethod());
        $this->assertEquals(
            '/v3/balances',
            $request->getUri()->__toString()
        );
        $this->assertEquals(
            'API_KEY',
            $request->getHeaderLine('Api-Key')
        );
        $this->assertEquals(
            hash('sha512', ''),
            $request->getHeaderLine('Api-Content-Hash')
        );
        $this->assertNotEmpty($request->getHeaderLine('Api-Timestamp'));
        $this->assertNotEmpty($request->getHeaderLine('Api-Signature'));
    }


    private function createApi(): Account
    {
        return new Account($this->getMockClient(true));
    }

    public function testGetBalance()
    {
        $this->createApi()->getBalance('BTC');
        $request = $this->getLastRequest();

        $this->assertEquals(
            '/v3/balances/BTC',
            $request->getUri()->__toString()
        );
        $this->assertEquals(
            'API_KEY',
            $request->getHeaderLine('Api-Key')
        );
        $this->assertEquals(
            hash('sha512', ''),
            $request->getHeaderLine('Api-Content-Hash')
        );
        $this->assertNotEmpty($request->getHeaderLine('Api-Timestamp'));
        $this->assertNotEmpty($request->getHeaderLine('Api-Signature'));
    }

    public function testGetDepositAddress()
    {
        $this->createApi()->getDepositAddress('BTC');
        $request = $this->getLastRequest();

        $this->assertEquals(
            '/v3/addresses/BTC',
            $request->getUri()->__toString()
        );
        $this->assertEquals(
            'API_KEY',
            $request->getHeaderLine('Api-Key')
        );
        $this->assertEquals(
            hash('sha512', ''),
            $request->getHeaderLine('Api-Content-Hash')
        );
        $this->assertNotEmpty($request->getHeaderLine('Api-Timestamp'));
        $this->assertNotEmpty($request->getHeaderLine('Api-Signature'));
    }

    public function testSetDepositAddress()
    {
        $this->createApi()->setDepositAddress('BTC');
        $request = $this->getLastRequest();

        $this->assertEquals(
            '/v3/addresses',
            $request->getUri()->__toString()
        );
        $this->assertEquals(
            'API_KEY',
            $request->getHeaderLine('Api-Key')
        );
        $this->assertEquals(
            hash('sha512', $request->getBody()->__toString()),
            $request->getHeaderLine('Api-Content-Hash')
        );
        $this->assertNotEmpty($request->getHeaderLine('Api-Timestamp'));
        $this->assertNotEmpty($request->getHeaderLine('Api-Signature'));
    }

    public function testWithdraw()
    {
        $this->createApi()->withdraw('BTC', 1.40, '12rwaw7p4eTQ3DL5gu4fSYYx3M3kZxxQVn', 'paymentId');

        $request = $this->getLastRequest();
        $this->assertEquals(
            '/v3/withdrawals',
            $request->getUri()->__toString()
        );
        $this->assertEquals(
            'API_KEY',
            $request->getHeaderLine('Api-Key')
        );
        $this->assertEquals(
            hash('sha512', $request->getBody()->__toString()),
            $request->getHeaderLine('Api-Content-Hash')
        );
        $this->assertNotEmpty($request->getHeaderLine('Api-Timestamp'));
        $this->assertNotEmpty($request->getHeaderLine('Api-Signature'));
    }

    public function testGetOrder()
    {
        $this->createApi()->getOrder('251c48e7-95d4-d53f-ad76-a7c6547b74ca9');

        $request = $this->getLastRequest();
        $this->assertEquals(
            '/v3/orders/251c48e7-95d4-d53f-ad76-a7c6547b74ca9',
            $request->getUri()->__toString()
        );
        $this->assertEquals(
            'API_KEY',
            $request->getHeaderLine('Api-Key')
        );
        $this->assertEquals(
            hash('sha512', ''),
            $request->getHeaderLine('Api-Content-Hash')
        );
        $this->assertNotEmpty($request->getHeaderLine('Api-Timestamp'));
        $this->assertNotEmpty($request->getHeaderLine('Api-Signature'));
    }

    public function testGetOrderHistory()
    {
        $this->createApi()->getOrderHistory('BTC-LTC', ['pageSize' => 10]);

        $request = $this->getLastRequest();
        $this->assertEquals(
            '/v3/orders/closed?marketSymbol=BTC-LTC&pageSize=10',
            $request->getUri()->__toString()
        );
        $this->assertEquals(
            'API_KEY',
            $request->getHeaderLine('Api-Key')
        );
        $this->assertEquals(
            hash('sha512', ''),
            $request->getHeaderLine('Api-Content-Hash')
        );
        $this->assertNotEmpty($request->getHeaderLine('Api-Timestamp'));
        $this->assertNotEmpty($request->getHeaderLine('Api-Signature'));
    }

    public function testGetWithdrawalHistory()
    {
        $this->createApi()->getWithdrawalHistory('BTC');

        $request = $this->getLastRequest();
        $this->assertEquals(
            '/v3/withdrawals/closed?currencySymbol=BTC',
            $request->getUri()->__toString()
        );
        $this->assertEquals(
            'API_KEY',
            $request->getHeaderLine('Api-Key')
        );
        $this->assertEquals(
            hash('sha512', ''),
            $request->getHeaderLine('Api-Content-Hash')
        );
        $this->assertNotEmpty($request->getHeaderLine('Api-Timestamp'));
        $this->assertNotEmpty($request->getHeaderLine('Api-Signature'));
    }

    public function testGetDepositHistory()
    {
        $this->createApi()->getDepositHistory('BTC');

        $request = $this->getLastRequest();
        $this->assertEquals(
            '/v3/deposits/closed?currencySymbol=BTC',
            $request->getUri()->__toString()
        );
        $this->assertEquals(
            'API_KEY',
            $request->getHeaderLine('Api-Key')
        );
        $this->assertEquals(
            hash('sha512', ''),
            $request->getHeaderLine('Api-Content-Hash')
        );
        $this->assertNotEmpty($request->getHeaderLine('Api-Timestamp'));
        $this->assertNotEmpty($request->getHeaderLine('Api-Signature'));
    }
}