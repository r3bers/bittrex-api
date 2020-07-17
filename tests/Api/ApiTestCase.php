<?php

declare(strict_types=1);

namespace R3bers\BittrexApi\Tests\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use R3bers\BittrexApi\Middleware\Authentication;

class ApiTestCase extends TestCase
{
    private $transactions = [];
    private $mockClient = null;

    protected function getLastRequest(): ?RequestInterface
    {
        if (($count = count($this->transactions)) === 0) {
            return null;
        }

        return $this->transactions[$count - 1]['request'] ?? null;
    }

    protected function getMockClient($isPrivateClient = false)
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json', 'Sequence' => ['42']], json_encode(['fo' => 'bar'])),
        ]);

        $handlerStack = HandlerStack::create($mock);

        if ($isPrivateClient) $handlerStack->push(new Authentication('API_KEY', 'API_SECRET'));

        $handlerStack->push(Middleware::history($this->transactions));
        $this->mockClient = new Client(['handler' => $handlerStack]);

        return $this->mockClient;
    }

    protected function tearDown(): void
    {
        $this->mockClient = null;
        $this->transactions = [];
    }
}