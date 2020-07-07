<?php

declare(strict_types=1);

namespace R3bers\BittrexApi;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use R3bers\BittrexApi\Api\Account;
use R3bers\BittrexApi\Api\Market;
use R3bers\BittrexApi\Api\PublicApi;
use R3bers\BittrexApi\Exception\InvalidCredentialException;
use R3bers\BittrexApi\Middleware\Authentication;

class BittrexClient
{
    private const BASE_URI = 'https://api.bittrex.com';

    /** @var Client */
    private $publicClient;

    /** @var Client */
    private $privateClient;

    /** @var string */
    private $key = '';

    /** @var string */
    private $secret = '';

    /**
     * @param string $key
     * @param string $secret
     */
    public function setCredential(string $key, string $secret): void
    {
        $this->key = $key;
        $this->secret = $secret;
    }

    /**
     * @return PublicApi
     */
    public function public(): PublicApi
    {
        return new PublicApi($this->getPublicClient());
    }

    /**
     * @return Client
     */
    private function getPublicClient(): Client
    {
        return $this->publicClient ?: $this->createPublicClient();
    }

    /**
     * @return Client
     */
    private function createPublicClient(): Client
    {
        return new Client([
            'headers' => [
                'User-Agent' => 'r3bers/bittrex-api/1.3',
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'base_uri' => self::BASE_URI
        ]);
    }

    /**
     * @return Market
     * @throws InvalidCredentialException
     */
    public function market(): Market
    {
        return new Market($this->getPrivateClient());
    }

    /**
     * @return Client
     * @throws InvalidCredentialException
     */
    private function getPrivateClient(): Client
    {
        return $this->privateClient ?: $this->createPrivateClient();
    }

    /**
     * @return Client
     * @throws InvalidCredentialException
     */
    private function createPrivateClient(): Client
    {
        if (empty($this->key) || empty($this->secret)) {
            throw new InvalidCredentialException('Key and secret must be set for authenticated API');
        }
        $stack = HandlerStack::create();
        $stack->push(new Authentication($this->getKey(), $this->getSecret()));

        return new Client([
            'headers' => [
                'User-Agent' => 'r3bers/bittrex-api/1.3',
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'handler' => $stack,
            'base_uri' => self::BASE_URI
        ]);
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @return Account
     * @throws InvalidCredentialException
     */
    public function account(): Account
    {
        return new Account($this->getPrivateClient());
    }
}
