<?php

declare(strict_types=1);

namespace R3bers\BittrexApi\Api;

use Exception;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class PublicApi
 * @package R3bers\BittrexApi\Api
 */
class PublicApi extends Api
{
    /**
     * @return array
     * @throws Exception|GuzzleException
     */
    public function getMarkets(): array
    {
        return $this->rest('GET', '/markets');
    }

    /**
     * @return array
     * @throws Exception|GuzzleException
     */
    public function getCurrencies(): array
    {
        return $this->rest('GET', '/currencies');
    }

    /**
     * @return array
     * @throws Exception|GuzzleException
     */
    public function getTickers(): array
    {
        return $this->rest('GET', '/markets/tickers');
    }

    /**
     * @param string $market
     * @return array
     * @throws Exception|GuzzleException
     */
    public function getTicker(string $market): array
    {
        return $this->rest('GET', '/markets/' . $market . '/ticker');
    }

    /**
     * @return array
     * @throws Exception|GuzzleException
     */
    public function getMarketSummaries(): array
    {
        return $this->rest('GET', '/markets/summaries');
    }

    /**
     * @param string $market
     * @return array
     * @throws Exception|GuzzleException
     */
    public function getMarketSummary(string $market): array
    {
        return $this->rest('GET', '/markets/' . $market . '/summary');
    }

    /**
     * @param string $market
     * @param int $depth
     * @return array
     * @throws Exception|GuzzleException
     */
    public function getOrderBook(string $market, int $depth = 25): array
    {
        $options = ['query' => ['depth' => $depth]];

        return $this->rest('GET', '/markets/' . $market . '/orderbook', $options);
    }

    /**
     * @param string $market
     * @return array
     * @throws Exception|GuzzleException
     */
    public function getMarketHistory(string $market): array
    {
        return $this->rest('GET', '/markets/' . $market . '/trades');
    }

    /**
     * @return array
     * @throws Exception|GuzzleException
     */
    public function ping(): array
    {
        return $this->rest('GET', '/ping');
    }

}