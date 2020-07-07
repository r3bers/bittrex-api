<?php

declare(strict_types=1);

namespace R3bers\BittrexApi\Api;

use Exception;

/**
 * Class Account
 * @package R3bers\BittrexApi\Api
 */
class Account extends Api
{
    /**
     * @return array
     * @throws Exception
     */
    public function getBalances(): array
    {
        return $this->get('/balances');
    }

    /**
     * @param string $currency
     * @return array
     * @throws Exception
     */
    public function getBalance(string $currency): array
    {
        return $this->get('/balances/' . $currency);
    }

    /**
     * @param string $currency
     * @return array
     * @throws Exception
     */
    public function getDepositAddress(string $currency): array
    {
        return $this->get('/addresses/' . $currency);
    }

    /**
     * @param string $currency
     * @return array
     * @throws Exception
     */
    public function setDepositAddress(string $currency): array
    {
        $NewAddress = [
            'currencySymbol' => $currency
        ];

        return $this->post('/addresses', json_encode($NewAddress));
    }

    /**
     * @param string $currency
     * @param float $quantity
     * @param string $address
     * @param string|null $paymentId
     * @return array
     * @throws Exception
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

        return $this->post('/withdrawals', json_encode($newWithdrawal));
    }

    /**
     * @param string $uuid
     * @return array
     * @throws Exception
     */
    public function getOrder(string $uuid): array
    {
        return $this->get('/orders/' . $uuid);
    }

    /**
     * @param string|null $marketSymbol
     * @return array
     * @throws Exception
     */
    public function getOrderHistory(?string $marketSymbol = null): array
    {
        $parameters = [];

        if (!is_null($marketSymbol)) $parameters['marketSymbol'] = $marketSymbol;

        return $this->get('/orders/closed', $parameters);
    }

    /**
     * @param string|null $currencySymbol
     * @param string|null $status
     * @return array
     * @throws Exception
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
     * @throws Exception
     */
    private function getHistory(string $whatHistory, ?string $currencySymbol, ?string $status): array
    {
        $parameters = [];

        if (!is_null($currencySymbol)) $parameters['currencySymbol'] = $currencySymbol;
        if (!is_null($status)) $parameters['status'] = $status;

        return $this->get('/' . $whatHistory . '/closed', $parameters);
    }

    /**
     * @param string|null $currencySymbol
     * @param string|null $status
     * @return array
     * @throws Exception
     */
    public function getDepositHistory(?string $currencySymbol = null, ?string $status = null): array
    {
        return $this->getHistory('deposits', $currencySymbol, $status);
    }
}
