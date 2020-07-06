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
     * @param string $uri
     * @param array $query
     * @return array
     * @throws Exception
     */
    public function get(string $uri, array $query = []): array
    {
        $response = $this->client->request('GET', $this->endpoint . $this->version
            . $uri, ['query' => $query]);

        return $this->transformer->transform($response);
    }

    /**
     * @param string $uri
     * @param array $query
     * @return array
     * @throws Exception
     */
    public function post(string $uri, array $query = []): array
    {
        $response = $this->client->request('POST', $this->endpoint . $this->version
            . $uri, ['query' => $query]);

        return $this->transformer->transform($response);
    }

    /**
     * @param string $uri
     * @param array $query
     * @return array
     * @throws Exception
     */
    public function delete(string $uri, array $query = []): array
    {
        $response = $this->client->request('DELETE', $this->endpoint . $this->version
            . $uri, ['query' => $query]);

        return $this->transformer->transform($response);
    }
}
