<?php

declare(strict_types=1);

namespace R3bers\BittrexApi;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use R3bers\BittrexApi\Api\Account;
use R3bers\BittrexApi\Api\Batch;
use R3bers\BittrexApi\Api\Market;
use R3bers\BittrexApi\Api\PublicApi;
use R3bers\BittrexApi\Exception\InvalidCredentialException;
use R3bers\BittrexApi\Middleware\Authentication;

class BittrexClient
{
    /**
     * Main URL to Bittrex Exchange
     */
    private const BASE_URI = 'https://api.bittrex.com';

    private const CLIENT_HEADER = [
        'User-Agent' => 'r3bers/bittrex-api/1.3.1',
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];

    /** @var Client */
    private $publicClient;

    /** @var Client */
    private $privateClient;

    /** @var string */
    private $key = '';

    /** @var string */
    private $secret = '';

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
        $this->publicClient = new Client([
            'headers' => self::CLIENT_HEADER,
            'base_uri' => self::BASE_URI
        ]);

        return $this->publicClient;
    }

    /**
     * @param string $key
     * @param string $secret
     * @throws InvalidCredentialException
     */
    public function setCredential(string $key, string $secret): void
    {

        if (!($this->isValidMd5($key) and $this->isValidMd5($secret)))
            throw new InvalidCredentialException('API Key and Secret have bad format');

        $this->key = $key;
        $this->secret = $secret;
    }

    /**
     * @param string $md5
     * @return bool
     */
    public function isValidMd5(string $md5 = ''): bool
    {
        return (preg_match('/^[a-f0-9]{32}$/', $md5) === 1);
    }

    /**
     * @return Batch
     * @throws InvalidCredentialException
     */
    public function batch(): Batch
    {
        return new Batch($this->getPrivateClient());
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
        if (!$this->haveValidCredentials())
            throw new InvalidCredentialException('Key and secret must be set before call Private API');

        $stack = HandlerStack::create();
        $stack->push(new Authentication($this->key, $this->secret));

        $this->privateClient = new Client([
            'headers' => self::CLIENT_HEADER,
            'handler' => $stack,
            'base_uri' => self::BASE_URI
        ]);

        return $this->privateClient;
    }

    /**
     * @return bool
     */
    public function haveValidCredentials(): bool
    {
        return (!empty($this->key) and !empty($this->secret) and $this->isValidMd5($this->key) and $this->isValidMd5($this->secret));
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
     * @return Account
     * @throws InvalidCredentialException
     */
    public function account(): Account
    {
        return new Account($this->getPrivateClient());
    }
}