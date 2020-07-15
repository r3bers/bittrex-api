<?php

declare(strict_types=1);

namespace R3bers\BittrexApi\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use R3bers\BittrexApi\Exception\TransformResponseException;
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
     * @param bool|null $needHeader Whenever you need response header in your data
     * @return array
     * @throws GuzzleException
     * @throws TransformResponseException
     */
    public function rest(string $method, string $uri, array $options = [], ?bool $needHeader = null): array
    {
        $response = $this->client->request($method, $this->endpoint . $this->version . $uri, $options);

        return $this->transformer->transform($response, $needHeader, $method == 'HEAD');
    }

}
