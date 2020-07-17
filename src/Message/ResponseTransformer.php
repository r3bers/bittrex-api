<?php

declare(strict_types=1);

namespace R3bers\BittrexApi\Message;

use Psr\Http\Message\ResponseInterface;
use R3bers\BittrexApi\Exception\TransformResponseException;

/**
 * Class ResponseTransformer
 * @package R3bers\BittrexApi\Message
 */
class ResponseTransformer
{
    /**
     * @param ResponseInterface $response
     * @param bool|null $needSequence
     * @return array
     * @throws TransformResponseException
     */
    public function transform(ResponseInterface $response, ?bool $needSequence = null): array
    {
        $body = (string)$response->getBody();
        if (strpos($response->getHeaderLine('Content-Type'), 'application/json') === 0) {
            $content = json_decode($body, true);
            if (JSON_ERROR_NONE === json_last_error()) {
                if ($needSequence)
                    $content = array_merge($content, $this->transformHeader($response));
                return $content;
            }

            throw new TransformResponseException('Error transforming response to array. JSON_ERROR: '
                . json_last_error());
        }

        throw new TransformResponseException('Error transforming response to array. Content-Type 
            is not application/json');
    }

    /**
     * @param ResponseInterface $response
     * @return array
     * @throws TransformResponseException
     */
    public function transformHeader(ResponseInterface $response): array
    {
        $responseHeaders = $response->getHeaders();
        if (!(isset($responseHeaders['Sequence'][0]) and is_numeric($responseHeaders['Sequence'][0])))
            throw new TransformResponseException('Error getting sequence from response headers.');
        else {
            $content = [];
            $content['Sequence'] = (int)$responseHeaders['Sequence'][0];
            return $content;
        }
    }
}