<?php

declare(strict_types=1);

namespace R3bers\BittrexApi\Api;

use Exception;
use GuzzleHttp\Client;
use R3bers\BittrexApi\Message\ResponseTransformer;

/**
 * Class Api
 * @package R3bers\BittrexApi\Api
 */
class Api
{
    /** @var Client */
    protected $client;
    /** @var ResponseTransformer */
    protected $transformer;
    /** @var string */
    private $version = 'v3';
    private $endpoint = '/';

    /**
     * Api constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->transformer = new ResponseTransformer();
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return array
     * @throws Exception
     */
    public function rest(string $method, string $uri, array $options = []): array
    {
        $response = $this->client->request($method, $this->endpoint . $this->version . $uri, $options);

        return $this->transformer->transform($response);
    }

}
