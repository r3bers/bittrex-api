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
     * @param bool|null $needHeader Whenever you need response header in your data
     * @param bool|null $noBody When HEAD Method used you dont'need to parse response Body
     * @return array
     * @throws TransformResponseException
     */
    public function transform(ResponseInterface $response, ?bool $needHeader = null, ?bool $noBody = null): array
    {
        $content = [];
        if (is_null($noBody) or !$noBody) {
            $body = (string)$response->getBody();
            if (strpos($response->getHeaderLine('Content-Type'), 'application/json') === 0) {
                $content = json_decode($body, true);
                if (!(JSON_ERROR_NONE === json_last_error()))
                    throw new TransformResponseException('Error transforming response to array. JSON_ERROR: ' . json_last_error());
            } else
                throw new TransformResponseException('Error transforming response to array. Content-Type is not application/json');
        }
        if (!is_null($needHeader) and $needHeader) {
            $content['responseHeaders'] = $response->getHeaders();
        }
        return $content;
    }
}
