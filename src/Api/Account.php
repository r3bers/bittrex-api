<?php

declare(strict_types=1);

namespace R3bers\BittrexApi\Api;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use R3bers\BittrexApi\Exception\TransformResponseException;

/**
 * Class Account
 * @package R3bers\BittrexApi\Api
 */
class Account extends Api
{
    /** https://bittrex.github.io/api/v3#operation--account-volume-get
     * @return array
     * @throws GuzzleException
     * @throws TransformResponseException
     */
    public function getVolume(): array
    {
        return $this->rest('GET', '/account/volume');
    }

    /** https://bittrex.github.io/api/v3#operation--balances-get
     * @param bool|null $needSequence
     * @return array
     * @throws GuzzleException
     * @throws TransformResponseException
     */
    public function getBalances(?bool $needSequence = null): array
    {
        return $this->rest('GET', '/balances', [], $needSequence);
    }

    /** https://bittrex.github.io/api/v3#operation--balances-head
     * @return int
     * @throws GuzzleException
     * @throws TransformResponseException
     */
    public function headBalances(): int
    {
        $responseArray = $this->rest('HEAD', '/balances');
        return $responseArray['Sequence'];
    }

    /** https://bittrex.github.io/api/v3#operation--balances--currencySymbol--get
     * @param string $currency
     * @param bool|null $needSequence
     * @return array
     * @throws GuzzleException
     * @throws TransformResponseException
     */
    public function getBalance(string $currency, ?bool $needSequence = null): array
    {
        return $this->rest('GET', '/balances/' . $currency, [], $needSequence);
    }

    /** https://bittrex.github.io/api/v3#operation--addresses-get
     * @param string $currency
     * @return array
     * @throws Exception|GuzzleException
     */
    public function getDepositAddress(string $currency): array
    {
        return $this->rest('GET', '/addresses/' . $currency);
    }

    /** https://bittrex.github.io/api/v3#operation--addresses-post
     * @param string $currency
     * @return array
     * @throws Exception|GuzzleException
     */
    public function setDepositAddress(string $currency): array
    {
        $options = ['body' => json_encode(['currencySymbol' => $currency])];

        return $this->rest('POST', '/addresses', $options);
    }

    /** https://bittrex.github.io/api/v3#operation--withdrawals-post
     * @param string $currency
     * @param float $quantity
     * @param string $address
     * @param string|null $paymentId
     * @return array
     * @throws Exception|GuzzleException
     */
    public function withdraw(string $currency, float $quantity, string $address, ?string $paymentId = null): array
    {
        $newWithdrawal = [
            'currencySymbol' => $currency,
            'quantity' => $quantity,
            'cryptoAddress' => $address,
        ];

        if (!is_null($paymentId)) {
            $newWithdrawal['cryptoAddressTag'] = $paymentId;
        }

        $options = ['body' => json_encode($newWithdrawal)];
        return $this->rest('POST', '/withdrawals', $options);
    }

    /** https://bittrex.github.io/api/v3#operation--orders--orderId--get
     * @param string $uuid
     * @return array
     * @throws Exception|GuzzleException
     */
    public function getOrder(string $uuid): array
    {
        return $this->rest('GET', '/orders/' . $uuid);
    }

    /** https://bittrex.github.io/api/v3#operation--orders-closed-get
     * @param string|null $marketSymbol
     * @param array|null $pagination
     * @return array
     * @throws GuzzleException
     * @throws TransformResponseException
     */
    public function getOrderHistory(?string $marketSymbol = null, ?array $pagination = null): array
    {
        $options = [];
        if (!is_null($marketSymbol)) $options['query'] = ['marketSymbol' => $marketSymbol];
        if (is_array($pagination))
            foreach ($pagination as $key => $value)
                $options['query'][$key] = $value;

        return $this->rest('GET', '/orders/closed', $options);
    }

    /** https://bittrex.github.io/api/v3#operation--withdrawals-closed-get
     * @param string|null $currencySymbol
     * @param string|null $status
     * @return array
     * @throws Exception|GuzzleException
     */
    public function getWithdrawalHistory(?string $currencySymbol = null, ?string $status = null): array
    {
        return $this->getHistory('withdrawals', $currencySymbol, $status);
    }

    /**
     * @param string $whatHistory
     * @param string|null $currencySymbol
     * @param string|null $status
     * @return array
     * @throws GuzzleException | TransformResponseException
     */
    private function getHistory(string $whatHistory, ?string $currencySymbol, ?string $status): array
    {
        $options = [];

        if (!is_null($currencySymbol)) $options['query'] = ['currencySymbol' => $currencySymbol];
        if (!is_null($status)) $options['query'] = ['status' => $status];

        return $this->rest('GET', '/' . $whatHistory . '/closed', $options);
    }

    /** https://bittrex.github.io/api/v3#operation--deposits-closed-get
     * @param string|null $currencySymbol
     * @param string|null $status
     * @return array
     * @throws Exception|GuzzleException
     */
    public function getDepositHistory(?string $currencySymbol = null, ?string $status = null): array
    {
        return $this->getHistory('deposits', $currencySymbol, $status);
    }
}