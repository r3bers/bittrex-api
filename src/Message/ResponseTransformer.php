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
     * @param bool|null $onlyHeader If null header not included in response to back compatibility, if true only header return.
     * @return array
     * @throws TransformResponseException
     */
    public function transform(ResponseInterface $response, ?bool $onlyHeader = null): array
    {

        $content = [];

        if (isset($onlyHeader)) {
            $responseHeaders = $response->getHeaders();
            if (isset($responseHeaders['Sequence'][0]) and is_numeric($responseHeaders['Sequence'][0])) {
                $content['Sequence'] = (int)$responseHeaders['Sequence'][0];
            }else{
                throw new TransformResponseException('Error getting sequence from response headers.');
            }
        }

        if (is_null($onlyHeader) or !$onlyHeader) {
            if (strpos($response->getHeaderLine('Content-Type'), 'application/json') === 0) {
                $body = (string)$response->getBody();
                $bodyArray = json_decode($body, true);
                if (json_last_error() != JSON_ERROR_NONE) {
                    throw new TransformResponseException('Error transforming response to array. JSON_ERROR: ' . json_last_error());
                } else {
                    $content = array_merge($content, $bodyArray);
                }
            } else {
                throw new TransformResponseException('Error transforming response to array. Content-Type is not application/json');
            }
        }

        return $content;

    }
}
