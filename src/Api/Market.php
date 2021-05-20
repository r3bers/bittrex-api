<?php

declare(strict_types=1);

namespace R3bers\BittrexApi\Api;

use GuzzleHttp\Exception\GuzzleException;
use R3bers\BittrexApi\Exception\TransformResponseException;

/**
 * Class Market
 * @package R3bers\BittrexApi\Api
 */
class Market extends Api
{
    /** https://bittrex.github.io/api/v3#operation--orders-post
     * @param string $market
     * @param float $quantity
     * @param float $rate
     * @param bool $useAwards
     * @return array
     * @throws GuzzleException|TransformResponseException
     */
    public function buyLimit(string $market, float $quantity, float $rate, bool $useAwards = false): array
    {
        $newOrder = [
            'marketSymbol' => $market,
            'direction' => 'BUY',
            'type' => 'LIMIT',
            'quantity' => $quantity,
            'limit' => $rate,
            'timeInForce' => 'GOOD_TIL_CANCELLED',
            'useAwards' => $useAwards

        ];
        $options = ['body' => json_encode($newOrder)];

        return $this->rest('POST', '/orders', $options);
    }

    /** https://bittrex.github.io/api/v3#operation--orders-post
     * @param string $market
     * @param float $quantity
     * @param float $rate
     * @param bool $useAwards
     * @return array
     * @throws GuzzleException|TransformResponseException
     */
    public function sellLimit(string $market, float $quantity, float $rate, bool $useAwards = false): array
    {
        $newOrder = [
            'marketSymbol' => $market,
            'direction' => 'SELL',
            'type' => 'LIMIT',
            'quantity' => $quantity,
            'limit' => $rate,
            'timeInForce' => 'GOOD_TIL_CANCELLED',
            'useAwards' => $useAwards

        ];
        $options = ['body' => json_encode($newOrder)];

        return $this->rest('POST', '/orders', $options);
    }

    /** https://bittrex.github.io/api/v3#operation--orders--orderId--delete
     * @param string $uuid
     * @return array
     * @throws GuzzleException|TransformResponseException
     */
    public function cancel(string $uuid): array
    {
        return $this->rest('DELETE', '/orders/' . $uuid);
    }

    /** https://bittrex.github.io/api/v3#operation--orders-open-get
     * @param string|null $market
     * @param bool|null $needSequence if true additional member of array named Sequence added to return
     * @return array
     * @throws GuzzleException|TransformResponseException
     */
    public function getOpenOrders(?string $market = null, ?bool $needSequence = null): array
    {
        $options = [];
        if (!is_null($market)) $options['query'] = ['marketSymbol' => $market];

        return $this->rest('GET', '/orders/open', $options, ($needSequence));
    }

    /** https://bittrex.github.io/api/v3#operation--orders-open-head
     * @return int Current Sequence of Orders
     * @throws GuzzleException
     * @throws TransformResponseException
     */
    public function headOpenOrders(): int
    {
        $responseArray = $this->rest('HEAD', '/orders/open', [], true);
        return $responseArray['Sequence'];
    }
}