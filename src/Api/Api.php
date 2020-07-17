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
     * @param bool|null $onlyHeader
     * null — When only body return
     * false — When response header in return data with body
     * true — When only header return
     * @return array
     * @throws GuzzleException
     * @throws TransformResponseException
     */
    public function rest(string $method, string $uri, array $options = [], ?bool $onlyHeader = null): array
    {
        $response = $this->client->request($method, $this->endpoint . $this->version . $uri, $options);

        if ($method === 'HEAD') $onlyHeader = true;
        return $this->transformer->transform($response, $onlyHeader);
    }

}
