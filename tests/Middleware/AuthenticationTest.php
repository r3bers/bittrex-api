<?php

declare(strict_types=1);

namespace R3bers\BittrexApi\Tests\Middleware;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use R3bers\BittrexApi\Middleware\Authentication;

class AuthenticationTest extends TestCase
{
    private const API_KEY = 'API_KEY';
    private const API_SECRET = 'API_SECRET';
    private const BASE_URI = 'https://api.bittrex.com';
    private const URI = '/v3/account/getbalances';
    private const METHOD = 'GET';

    public function testUri()
    {
        $request = $this->getHandledRequest(new Request(self::METHOD, self::URI, []));
        $this->assertEquals(
            self::URI,
            $request->getUri()->__toString()
        );
    }

    private function getHandledRequest(RequestInterface $request): RequestInterface
    {
        $middleware = new Authentication(self::API_KEY, self::API_SECRET, self::BASE_URI);
        return $middleware(function (RequestInterface $request) {
            return $request;
        })($request);
    }

    public function testHeader()
    {
        $request = $this->getHandledRequest(new Request(self::METHOD, self::URI, []));
        $this->assertEquals(
            self::API_KEY,
            $request->getHeaderLine('Api-Key')
        );
        $this->assertEquals(
            hash('sha512', ''),
            $request->getHeaderLine('Api-Content-Hash')
        );
        $this->assertNotEmpty($request->getHeaderLine('Api-Timestamp'));

        $pre_sign = $request->getHeaderLine('Api-Timestamp') .
            self::BASE_URI .
            self::URI .
            self::METHOD .
            hash('sha512', '');

        $sign = hash_hmac('sha512', $pre_sign, self::API_SECRET);

        $this->assertEquals($sign, $request->getHeaderLine('Api-Signature'));
    }
}
