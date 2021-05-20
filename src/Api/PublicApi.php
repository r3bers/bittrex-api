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
    /** https://bittrex.github.io/api/v3#operation--markets-get
     * @return array
     * @throws Exception|GuzzleException
     */
    public function getMarkets(): array
    {
        return $this->rest('GET', '/markets');
    }

    /** https://bittrex.github.io/api/v3#operation--currencies-get
     * @return array
     * @throws Exception|GuzzleException
     */
    public function getCurrencies(): array
    {
        return $this->rest('GET', '/currencies');
    }

    /** https://bittrex.github.io/api/v3#operation--markets-tickers-get
     * @return array
     * @throws Exception|GuzzleException
     */
    public function getTickers(): array
    {
        return $this->rest('GET', '/markets/tickers');
    }

    /** https://bittrex.github.io/api/v3#operation--markets--marketSymbol--ticker-get
     * @param string $market
     * @return array
     * @throws Exception|GuzzleException
     */
    public function getTicker(string $market): array
    {
        return $this->rest('GET', '/markets/' . $market . '/ticker');
    }

    /** https://bittrex.github.io/api/v3#operation--markets-summaries-get
     * @return array
     * @throws Exception|GuzzleException
     */
    public function getMarketSummaries(): array
    {
        return $this->rest('GET', '/markets/summaries');
    }

    /** https://bittrex.github.io/api/v3#operation--markets--marketSymbol--summary-get
     * @param string $market
     * @return array
     * @throws Exception|GuzzleException
     */
    public function getMarketSummary(string $market): array
    {
        return $this->rest('GET', '/markets/' . $market . '/summary');
    }

    /** https://bittrex.github.io/api/v3#operation--markets--marketSymbol--orderbook-get
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

    /** https://bittrex.github.io/api/v3#operation--markets--marketSymbol--trades-get
     * @param string $market
     * @return array
     * @throws Exception|GuzzleException
     */
    public function getMarketHistory(string $market): array
    {
        return $this->rest('GET', '/markets/' . $market . '/trades');
    }

    /** https://bittrex.github.io/api/v3#operation--ping-get
     * @return array
     * @throws Exception|GuzzleException
     */
    public function ping(): array
    {
        return $this->rest('GET', '/ping');
    }

}