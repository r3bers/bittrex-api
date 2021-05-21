<?php

declare(strict_types=1);

namespace R3bers\BittrexApi\Api;

use Ds\Queue;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use R3bers\BittrexApi\Exception\TransformResponseException;

/**
 * Class Batch
 * @package R3bers\BittrexApi\Api
 */
class Batch extends Api
{
    /**
     * @var Queue
     */
    protected Queue $batchQueue;

    /**
     * Api constructor.
     */
    public function __construct(Client $client)
    {
        parent::__construct($client);
        $this->batchQueue = new Queue();
    }

    /** https://bittrex.github.io/api/v3#operation--batch-post
     *  https://bittrex.github.io/api/v3#operation--orders-post
     * @param string $market
     * @param float $quantity
     * @param float $rate
     * @param bool $useAwards
     */
    public function addBuyLimit(string $market, float $quantity, float $rate, bool $useAwards = false)
    {
        $addOrder = [
            "resource" => "order",
            "operation" => "post",
            'payload' => [
                'marketSymbol' => $market,
                'direction' => 'BUY',
                'type' => 'LIMIT',
                'quantity' => $quantity,
                'limit' => $rate,
                'timeInForce' => 'GOOD_TIL_CANCELLED',
                'useAwards' => $useAwards

            ]];
        $this->batchQueue->push($addOrder);
    }

    /** https://bittrex.github.io/api/v3#operation--batch-post
     *  https://bittrex.github.io/api/v3#operation--orders-post
     * @param string $market
     * @param float $quantity
     * @param float $rate
     * @param bool $useAwards
     */
    public function addSellLimit(string $market, float $quantity, float $rate, bool $useAwards = false)
    {
        $addOrder = [
            "resource" => "order",
            "operation" => "post",
            'payload' => [
                'marketSymbol' => $market,
                'direction' => 'SELL',
                'type' => 'LIMIT',
                'quantity' => $quantity,
                'limit' => $rate,
                'timeInForce' => 'GOOD_TIL_CANCELLED',
                'useAwards' => $useAwards

            ]];
        $this->batchQueue->push($addOrder);
    }

    /** https://bittrex.github.io/api/v3#operation--batch-post
     * https://bittrex.github.io/api/v3#operation--orders--orderId--delete
     * @param string $uuid
     */
    public function addCancel(string $uuid)
    {
        $addOrder = [
            "resource" => "order",
            "operation" => "delete",
            'payload' => [
                'id' => $uuid
            ]];
        $this->batchQueue->push($addOrder);
    }

    /** https://bittrex.github.io/api/v3#operation--batch-post
     * Create a new batch request. Currently batch requests are limited to placing and cancelling orders. The request model corresponds to the equivalent individual operations.
     * Batch operations are executed sequentially in the order they are listed in the request. The response will return one result for each operation in the request in the same order.
     * The status and response payload are the same as the responses would be if individual API requests were made for each operation
     * @throws TransformResponseException | GuzzleException
     */
    public function executeBatch(): array
    {
        $queue = [];
        while (!$this->batchQueue->isEmpty())
            $queue[] = $this->batchQueue->pop();
        $options = ['body' => json_encode($queue)];
        return $this->rest('POST', '/batch', $options);
    }
}