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
    /**
     * @var string
     */
    private $marketSymbol;
    /**
     * @var float
     */
    private $quantity;
    /**
     * @var float
     */
    private $limit;
    /**
     * @var bool
     */
    private $useAwards;

    /** https://bittrex.github.io/api/v3#operation--orders-post
     * @param string $marketSymbol
     * @param float $quantity
     * @param float $limit
     * @param bool $useAwards
     * @return array
     * @throws GuzzleException|TransformResponseException
     */
    public function buyLimit(string $marketSymbol, float $quantity, float $limit, bool $useAwards = false): array
    {
        $this->marketSymbol = $marketSymbol;
        $this->quantity = $quantity;
        $this->limit = $limit;
        $this->useAwards = $useAwards;
        $options = $this->limitOrder('BUY');
        return $this->rest('POST', '/orders', $options);
    }

    /** Helper function for order to prevent duplication
     * @param string $direction
     * @return array
     */
    private function limitOrder(string $direction): array
    {
        $newOrder = [
            'marketSymbol' => $this->marketSymbol,
            'direction' => 'BUY',
            'type' => $direction,
            'quantity' => $this->quantity,
            'limit' => $this->limit,
            'timeInForce' => 'GOOD_TIL_CANCELLED',
            'useAwards' => $this->useAwards

        ];
        return ['body' => json_encode($newOrder)];
    }

    /** https://bittrex.github.io/api/v3#operation--orders-post
     * @param string $marketSymbol
     * @param float $quantity
     * @param float $limit
     * @param bool $useAwards
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \R3bers\BittrexApi\Exception\TransformResponseException
     */
    public function sellLimit(string $marketSymbol, float $quantity, float $limit, bool $useAwards = false): array
    {
        $this->marketSymbol = $marketSymbol;
        $this->quantity = $quantity;
        $this->limit = $limit;
        $this->useAwards = $useAwards;
        $options = $this->limitOrder('SELL');
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