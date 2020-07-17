<?php

declare(strict_types=1);

namespace R3bers\BittrexApi\Tests\Api;

use R3bers\BittrexApi\Api\PublicApi;

class PublicApiTest extends ApiTestCase
{
    public function testGetMarkets()
    {
        $this->createApi()->getMarkets();
        $request = $this->getLastRequest();

        $this->assertEquals('/v3/markets', $request->getUri()->__toString());
    }

    private function createApi(): PublicApi
    {
        return new PublicApi($this->getMockClient());
    }

    public function testGetCurrencies()
    {
        $this->createApi()->getCurrencies();
        $request = $this->getLastRequest();

        $this->assertEquals('/v3/currencies', $request->getUri()->__toString());
    }

    public function testGetTickers()
    {
        $this->createApi()->getTickers();
        $request = $this->getLastRequest();
        $this->assertEquals('/v3/markets/tickers', $request->getUri()->__toString());
    }

    public function testGetTicker()
    {
        $this->createApi()->getTicker('BTC-LTC');
        $request = $this->getLastRequest();
        $this->assertEquals('/v3/markets/BTC-LTC/ticker', $request->getUri()->__toString());
    }

    public function testGetMarketSummaries()
    {
        $this->createApi()->getMarketSummaries();

        $request = $this->getLastRequest();
        $this->assertEquals('/v3/markets/summaries', $request->getUri()->__toString());
    }

    public function testGetMarketSummary()
    {
        $this->createApi()->getMarketSummary('BTC-LTC');

        $request = $this->getLastRequest();
        $this->assertEquals('/v3/markets/BTC-LTC/summary', $request->getUri()->__toString());
    }

    public function testGetOrderBook()
    {
        $this->createApi()->getOrderBook('BTC-LTC');

        $request = $this->getLastRequest();
        $this->assertEquals('/v3/markets/BTC-LTC/orderbook?depth=25', $request->getUri()->__toString());
    }

    public function testGetMarketHistory()
    {
        $this->createApi()->getMarketHistory('BTC-LTC');

        $request = $this->getLastRequest();
        $this->assertEquals('/v3/markets/BTC-LTC/trades', $request->getUri()->__toString());
    }

    public function testPing()
    {
        $this->createApi()->ping();

        $request = $this->getLastRequest();
        $this->assertEquals('/v3/ping', $request->getUri()->__toString());
    }
}