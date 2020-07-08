<?php

declare(strict_types=1);

namespace R3bers\BittrexApi\Api;

use Exception;

/**
 * Class Market
 * @package R3bers\BittrexApi\Api
 */
class Market extends Api
{
    /**
     * @param string $market
     * @param float $quantity
     * @param float $rate
     * @param bool $useAwards
     * @return array
     * @throws Exception
     */
    public function buyLimit(string $market, float $quantity, float $rate, $useAwards = true): array
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

    /**
     * @param string $market
     * @param float $quantity
     * @param float $rate
     * @param bool $useAwards
     * @return array
     * @throws Exception
     */
    public function sellLimit(string $market, float $quantity, float $rate, $useAwards = true): array
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

    /**
     * @param string $uuid
     * @return array
     * @throws Exception
     */
    public function cancel(string $uuid): array
    {
        return $this->rest('DELETE', '/orders/' . $uuid);
    }

    /**
     * @param string|null $market
     * @return array
     * @throws Exception
     */
    public function getOpenOrders(?string $market = null): array
    {
        $options = [];
        if (!is_null($market)) $options['query'] = ['marketSymbol' => $market];

        return $this->rest('GET', '/orders/open', $options);
    }
}
