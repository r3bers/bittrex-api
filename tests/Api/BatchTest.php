<?php

declare(strict_types=1);

namespace R3bers\BittrexApi\Tests\Api;

use GuzzleHttp\Exception\GuzzleException;
use R3bers\BittrexApi\Api\Batch;
use R3bers\BittrexApi\Exception\TransformResponseException;

class BatchTest extends ApiTestCase
{

    /**
     * @throws TransformResponseException | GuzzleException
     */
    public function testBatch()
    {
        $result = $this->createApi();
        $result->addBuyLimit('BTC-USD', 1, 20000);
        $result->addSellLimit('BTC-USD', 2, 20050);
        $result->addCancel("83d4dd58-cba5-4fb4-881c-f77fc43009d9");
        $result->executeBatch();

        $request = $this->getLastRequest();
        $this->assertEquals(
            '/v3/batch',
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

        $expectedBody = '[{"resource":"order","operation":"post","payload":{"marketSymbol":"BTC-USD","direction":"BUY","type":"LIMIT","quantity":1,"limit":20000,"timeInForce":"GOOD_TIL_CANCELLED","useAwards":false}},{"resource":"order","operation":"post","payload":{"marketSymbol":"BTC-USD","direction":"SELL","type":"LIMIT","quantity":2,"limit":20050,"timeInForce":"GOOD_TIL_CANCELLED","useAwards":false}},{"resource":"order","operation":"delete","payload":{"id":"83d4dd58-cba5-4fb4-881c-f77fc43009d9"}}]';
        $actualBody = $request->getBody()->__toString();
        $this->assertEquals($expectedBody, $actualBody);
    }

    private function createApi(): Batch
    {
        return new Batch($this->getMockClient(true));
    }
}