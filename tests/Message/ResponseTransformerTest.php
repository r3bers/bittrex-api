<?php

declare(strict_types=1);

namespace R3bers\BittrexApi\Tests\Message;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use R3bers\BittrexApi\Exception\TransformResponseException;
use R3bers\BittrexApi\Message\ResponseTransformer;

class ResponseTransformerTest extends TestCase
{
    public function testTransform()
    {
        $transformer = new ResponseTransformer();
        $data = ['foo' => 'bar'];
        $response = new Response(200, ['Content-Type' => 'application/json'], json_encode($data));

        $this->assertEquals($data, $transformer->transform($response));
    }

    public function testTransformWithEmptyBody()
    {
        $transformer = new ResponseTransformer();
        $data = [];
        $response = new Response(200, ['Content-Type' => 'application/json'], json_encode($data));

        $this->assertEquals($data, $transformer->transform($response));
    }

    public function testTransformThrowTransformResponseException()
    {
        $transformer = new ResponseTransformer();
        $response = new Response(200, ['Content-Type' => 'application/json'], '');

        $this->expectException(TransformResponseException::class);

        $transformer->transform($response);
    }

    public function testTransformWithIncorrectContentType()
    {
        $transformer = new ResponseTransformer();
        $data = [];
        $response = new Response(200, ['Content-Type' => 'application/javascript'], json_encode($data));

        $this->expectException(TransformResponseException::class);

        $this->assertEquals($data, $transformer->transform($response));
    }

    public function testTransformWithIncorrectSequence()
    {
        $transformer = new ResponseTransformer();
        $data = [];
        $response = new Response(200, ['Content-Length' => '0', 'Sequence' => ['abc']], json_encode($data));

        $this->expectException(TransformResponseException::class);

        $this->assertEquals($data, $transformer->transform($response,true));
    }
}
