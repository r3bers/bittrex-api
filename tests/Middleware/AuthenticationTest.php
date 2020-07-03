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
    private const NONCE = '1585301777';
    private const URI = 'https://api.bittrex.com/api/v3/account/getbalances';
    private const METHOD = 'GET';

    public function testUri()
    {
        $request = $this->getHandledRequest(new Request(self::METHOD, self::URI, []));
        $this->assertEquals(
            self::URI . '?nonce=' . self::NONCE . '&apikey=' . self::API_KEY,
            $request->getUri()->__toString()
        );
    }

    private function getHandledRequest(RequestInterface $request): RequestInterface
    {
        $middleware = new Authentication(self::API_KEY, self::API_SECRET, self::NONCE);
        return $middleware(function (RequestInterface $request) {
            return $request;
        })($request);
    }

    public function testHeader()
    {
        $request = $this->getHandledRequest(new Request(self::METHOD, self::URI, []));
        $this->assertEquals(
            'd1ac335a359f508927edbec8ad8f9cd0d1e06a7e8960bdca815937478f4098ad623ba79a70c64fe6ac3051bae5f6adda4411682fe82cc6240646f1bb9e35976b',
            $request->getHeaderLine('apisign')
        );
    }
}
