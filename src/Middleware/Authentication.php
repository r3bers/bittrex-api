<?php

declare(strict_types=1);

namespace R3bers\BittrexApi\Middleware;

use Closure;
use Psr\Http\Message\RequestInterface;

class Authentication
{
    /** @var string */
    private $key;

    /** @var string */
    private $secret;

    /** @var string */
    private $subaccountId;

    /**
     * Authentication constructor.
     * @param string $key
     * @param string $secret
     * @param string $subaccountId
     */
    public function __construct(string $key, string $secret, ?string $subaccountId = null)
    {
        $this->key = $key;
        $this->secret = $secret;
        $this->subaccountId = $subaccountId;
    }

    /**
     * @param callable $next
     * @return Closure
     */
    public function __invoke(callable $next)
    {
        return function (RequestInterface $request, array $options = []) use ($next) {
            $timestamp = round(microtime(true) * 1000);
            $contentHash = $this->generateContentHash($request->getBody()->__toString());
            $pre_sign = $timestamp .
                $request->getUri()->__toString() .
                $request->getMethod() .
                $contentHash .
                $this->subaccountId;
            $sign = $this->generateSign($pre_sign);
            $request = $request->withAddedHeader('Api-Key', $this->key);
            $request = $request->withAddedHeader('Api-Timestamp', $timestamp);
            $request = $request->withAddedHeader('Api-Content-Hash', $contentHash);
            $request = $request->withAddedHeader('Api-Signature', $sign);
            if (!is_null($this->subaccountId)) {
                $request = $request->withAddedHeader('Api-Subaccount-Id', $this->subaccountId);
            }
            return $next($request, $options);
        };
    }

    /**
     * @param string $content
     * @return string
     */
    private function generateContentHash(string $content): string
    {
        return hash('sha512', $content);
    }

    /**
     * @param string $preSign
     * @return string
     */
    private function generateSign(string $preSign): string
    {
        return hash_hmac('sha512', $preSign, $this->secret);
    }


}
