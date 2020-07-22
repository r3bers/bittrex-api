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
    /**
     * @param bool|null $needSequence
     * @return array
     * @throws GuzzleException
     * @throws TransformResponseException
     */
    public function getBalances(?bool $needSequence = null): array
    {
        return $this->rest('GET', '/balances', [], $needSequence);
    }

    /**
     * @return int
     * @throws GuzzleException
     * @throws TransformResponseException
     */
    public function headBalances(): int
    {
        $responseArray = $this->rest('HEAD', '/balances');
        return $responseArray['Sequence'];
    }

    /**
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

    /**
     * @param string $currency
     * @return array
     * @throws Exception|GuzzleException
     */
    public function getDepositAddress(string $currency): array
    {
        return $this->rest('GET', '/addresses/' . $currency);
    }

    /**
     * @param string $currency
     * @return array
     * @throws Exception|GuzzleException
     */
    public function setDepositAddress(string $currency): array
    {
        $options = ['body' => json_encode(['currencySymbol' => $currency])];

        return $this->rest('POST', '/addresses', $options);
    }

    /**
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

    /**
     * @param string $uuid
     * @return array
     * @throws Exception|GuzzleException
     */
    public function getOrder(string $uuid): array
    {
        return $this->rest('GET', '/orders/' . $uuid);
    }

    /**
     * @param string|null $marketSymbol
     * @return array
     * @throws Exception|GuzzleException
     */
    public function getOrderHistory(?string $marketSymbol = null): array
    {
        $options = [];
        if (!is_null($marketSymbol)) $options['query'] = ['marketSymbol' => $marketSymbol];

        return $this->rest('GET', '/orders/closed', $options);
    }

    /**
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
     * @param string $currencySymbol
     * @param string $status
     * @return array
     * @throws Exception|GuzzleException
     */
    private function getHistory(string $whatHistory, ?string $currencySymbol, ?string $status): array
    {
        $options = [];

        if (!is_null($currencySymbol)) $options['query'] = ['currencySymbol' => $currencySymbol];
        if (!is_null($status)) $options['query'] = ['status' => $status];

        return $this->rest('GET', '/' . $whatHistory . '/closed', $options);
    }

    /**
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