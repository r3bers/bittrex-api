<?php

declare(strict_types=1);

namespace R3bers\BittrexApi\Tests\Api;

use R3bers\BittrexApi\Api\Market;

class MarketTest extends ApiTestCase
{
    public function testBuyLimit()
    {
        $this->createApi()->buyLimit('USDT-BTC', 1, 1);
        $request = $this->getLastRequest();

        $this->assertEquals(
            '/v3/orders',
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

    private function createApi(): Market
    {
        return new Market($this->getMockClient(true));
    }

    public function testSellLimit()
    {
        $this->createApi()->sellLimit('BTC-LTC', 1.2, 1.3);
        $request = $this->getLastRequest();

        $this->assertEquals(
            '/v3/orders',
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

    public function testCancel()
    {
        $this->createApi()->cancel('251c48e7-95d4-d53f-ad76-a7c6547b74ca9');
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

    public function testGetOpenOrders()
    {
        $this->createApi()->getOpenOrders();

        $request = $this->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals(
            '/v3/orders/open',
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

    public function testGetOpenOrdersWithMarket()
    {
        $this->createApi()->getOpenOrders('BTC-LTC');

        $request = $this->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals(
            '/v3/orders/open?marketSymbol=BTC-LTC',
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

    public function testHeadOpenOrders()
    {
        $this->createApi()->headOpenOrders();

        $request = $this->getLastRequest();
        $this->assertEquals('HEAD', $request->getMethod());
        $this->assertEquals(
            '/v3/orders/open',
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