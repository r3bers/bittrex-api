<?php

declare(strict_types=1);

namespace R3bers\BittrexApi\Api;

use Exception;

/**
 * Class PublicApi
 * @package R3bers\BittrexApi\Api
 */
class PublicApi extends Api
{
    /**
     * @return array
     * @throws Exception
     */
    public function getMarkets(): array
    {
        return $this->get('/markets');
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getCurrencies(): array
    {
        return $this->get('/currencies');
    }

    /**
     * @param string $market
     * @return array
     * @throws Exception
     */
    public function getTicker(string $market): array
    {
        return $this->get('/markets/' . $market . '/ticker');
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getMarketSummaries(): array
    {
        return $this->get('/markets/summaries');
    }

    /**
     * @param string $market
     * @return array
     * @throws Exception
     */
    public function getMarketSummary(string $market): array
    {
        return $this->get('/markets/' . $market . '/summary');
    }

    /**
     * @param string $market
     * @param int $depth
     * @return array
     * @throws Exception
     */
    public function getOrderBook(string $market, $depth = 25): array
    {
        $parameters = ['depth' => $depth];

        return $this->get('/markets/' . $market . '/orderbook', $parameters);
    }

    /**
     * @param string $market
     * @return array
     * @throws Exception
     */
    public function getMarketHistory(string $market)
    {
        return $this->get('/markets/' . $market . '/trades');
    }
}
