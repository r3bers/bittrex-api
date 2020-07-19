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
    /**
     * Main URL to Bittrex Exchange
     */
    private const BASE_URI = 'https://api.bittrex.com';

    /** @var Client */
    private $publicClient;

    /** @var Client */
    private $privateClient;

    /** @var string */
    private $key = '';

    /** @var string */
    private $secret = '';

    /** @var int */
    private $currentMinuteCount = 0;
    /**
     * @var int
     */
    private $lastAPITime;
    /**
     * @var int
     */
    private $prevMinuteCount = 0;

    /**
     * BittrexClient constructor.
     */
    public function __construct()
    {
        $this->lastAPITime = time();
    }

    /**
     * @return int
     */
    public function getCurrentMinuteCount(): int
    {
        return $this->currentMinuteCount;
    }

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
        $this->countAPI();
        return new PublicApi($this->getPublicClient());

    }

    /**
     * Counting in minute API
     */
    private function countAPI(): void
    {
        $currentTime = time();
        if (intdiv($this->lastAPITime, 60) < intdiv($currentTime, 60)) {
            $this->prevMinuteCount = $this->currentMinuteCount;
            $this->currentMinuteCount = 1;
        } else {
            $this->currentMinuteCount++;
            $this->lastAPITime = $currentTime;
        }
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
        $this->countAPI();
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
        $this->countAPI();
        return new Account($this->getPrivateClient());
    }

    /**
     * @return int
     */
    public function getPrevMinuteCount(): int
    {
        return $this->prevMinuteCount;
    }
}