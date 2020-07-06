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

        return $this->post('/orders', json_encode($newOrder));
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

        return $this->post('/orders', json_encode($newOrder));
    }

    /**
     * @param string $uuid
     * @return array
     * @throws Exception
     */
    public function cancel(string $uuid): array
    {
        return $this->delete('/orders/' . $uuid);
    }

    /**
     * @param string|null $market
     * @return array
     * @throws Exception
     */
    public function getOpenOrders(?string $market = null): array
    {
        $parameters = [];

        if (!is_null($market)) {
            $parameters['marketSymbol'] = $market;
        }

        return $this->get('/orders/open', $parameters);
    }
}
