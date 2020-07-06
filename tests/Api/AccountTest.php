<?php

declare(strict_types=1);

namespace R3bers\BittrexApi\Tests\Api;

use R3bers\BittrexApi\Api\Account;

class AccountTest extends ApiTestCase
{
    public function testGetBalances()
    {
        $this->createApi()->getBalances();
        $request = $this->getLastRequest();

        $this->assertEquals(
            '/v3/account/getbalances',
            $request->getUri()->__toString()
        );
        $this->assertEquals(
            'API_KEY',
            $request->getHeaderLine('Api-Key')
        );
        $this->assertEquals(
            hash('sha512',''),
            $request->getHeaderLine('Api-Content-Hash')
        );
        $this->assertNotEmpty($request->getHeaderLine('Api-Timestamp'));
        $this->assertNotEmpty($request->getHeaderLine('Api-Signature'));
    }

    public function testGetBalance()
    {
        $this->createApi()->getBalance('BTC');
        $request = $this->getLastRequest();

        $this->assertEquals(
            '/v3/account/getbalance?currency=BTC',
            $request->getUri()->__toString()
        );
        $this->assertEquals(
            'API_KEY',
            $request->getHeaderLine('Api-Key')
        );
        $this->assertEquals(
            hash('sha512',''),
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
            '/v3/account/getdepositaddress?currency=BTC',
            $request->getUri()->__toString()
        );
        $this->assertEquals(
            'API_KEY',
            $request->getHeaderLine('Api-Key')
        );
        $this->assertEquals(
            hash('sha512',''),
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
            '/v3/account/withdraw?currency=BTC&quantity=1.4&address=12rwaw7p4eTQ3DL5gu4fSYYx3M3kZxxQVn'
            . '&paymentid=paymentId',
            $request->getUri()->__toString()
        );
        $this->assertEquals(
            'API_KEY',
            $request->getHeaderLine('Api-Key')
        );
        $this->assertEquals(
            hash('sha512',''),
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
            '/v3/account/getorder?uuid=251c48e7-95d4-d53f-ad76-a7c6547b74ca9',
            $request->getUri()->__toString()
        );
        $this->assertEquals(
            'API_KEY',
            $request->getHeaderLine('Api-Key')
        );
        $this->assertEquals(
            hash('sha512',''),
            $request->getHeaderLine('Api-Content-Hash')
        );
        $this->assertNotEmpty($request->getHeaderLine('Api-Timestamp'));
        $this->assertNotEmpty($request->getHeaderLine('Api-Signature'));
    }

    public function testGetOrderHistory()
    {
        $this->createApi()->getOrderHistory('BTC-LTC');

        $request = $this->getLastRequest();
        $this->assertEquals(
            '/v3/account/getorderhistory?market=BTC-LTC',
            $request->getUri()->__toString()
        );
        $this->assertEquals(
            'API_KEY',
            $request->getHeaderLine('Api-Key')
        );
        $this->assertEquals(
            hash('sha512',''),
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
            '/v3/account/getwithdrawalhistory?currency=BTC',
            $request->getUri()->__toString()
        );
        $this->assertEquals(
            'API_KEY',
            $request->getHeaderLine('Api-Key')
        );
        $this->assertEquals(
            hash('sha512',''),
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
            '/v3/account/getdeposithistory?currency=BTC',
            $request->getUri()->__toString()
        );
        $this->assertEquals(
            'API_KEY',
            $request->getHeaderLine('Api-Key')
        );
        $this->assertEquals(
            hash('sha512',''),
            $request->getHeaderLine('Api-Content-Hash')
        );
        $this->assertNotEmpty($request->getHeaderLine('Api-Timestamp'));
        $this->assertNotEmpty($request->getHeaderLine('Api-Signature'));
    }

    private function createApi(): Account
    {
        return new Account($this->getMockClient(true));
    }
}
