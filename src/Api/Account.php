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

    public function getOrderHistory(?string $marketSymbol = null, ?string $nextPageToken = null, ?string $previousPageToken = null, ?string $pageSize = null,
                                    ?string $startDate = null, ?string $endDate = null): array
    {
        $parameters = $this->historyPagination($nextPageToken, $previousPageToken, $pageSize, $startDate, $endDate);

        if (!is_null($marketSymbol)) $parameters['marketSymbol'] = $marketSymbol;

        return $this->get('/orders/closed', $parameters);
    }

    /**
     * @param string|null $nextPageToken
     * @param string|null $previousPageToken
     * @param string|null $pageSize
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array
     */
    private function historyPagination(?string $nextPageToken = null, ?string $previousPageToken = null, ?string $pageSize = null, ?string $startDate = null,
                                       ?string $endDate = null): array
    {
        $pagination = [];

        if (!is_null($nextPageToken)) $pagination['nextPageToken'] = $nextPageToken;
        if (!is_null($previousPageToken)) $pagination['previousPageToken'] = $previousPageToken;
        if (!is_null($pageSize)) $pagination['pageSize'] = $pageSize;
        if (!is_null($startDate)) $pagination['startDate'] = $startDate;
        if (!is_null($endDate)) $pagination['endDate'] = $endDate;

        return $pagination;
    }

    /**
     * @param string|null $currencySymbol
     * @param string|null $status
     * @param string|null $nextPageToken
     * @param string|null $previousPageToken
     * @param string|null $pageSize
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array
     * @throws Exception
     */
    public function getWithdrawalHistory(?string $currencySymbol = null, ?string $status = null, ?string $nextPageToken = null, ?string $previousPageToken = null,
                                         ?string $pageSize = null, ?string $startDate = null, ?string $endDate = null): array
    {
        $parameters = $this->historyPagination($nextPageToken, $previousPageToken, $pageSize, $startDate, $endDate);

        if (!is_null($currencySymbol)) $parameters['currencySymbol'] = $currencySymbol;
        if (!is_null($status)) $parameters['status'] = $status;

        return $this->get('/withdrawals/closed', $parameters);
    }

    /**
     * @param string|null $currencySymbol
     * @param string|null $status
     * @param string|null $nextPageToken
     * @param string|null $previousPageToken
     * @param string|null $pageSize
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array
     * @throws Exception
     */
    public function getDepositHistory(?string $currencySymbol = null, ?string $status = null, ?string $nextPageToken = null, ?string $previousPageToken = null,
                                      ?string $pageSize = null, ?string $startDate = null, ?string $endDate = null): array
    {
        $parameters = $this->historyPagination($nextPageToken, $previousPageToken, $pageSize, $startDate, $endDate);

        if (!is_null($currencySymbol)) $parameters['currencySymbol'] = $currencySymbol;
        if (!is_null($status)) $parameters['status'] = $status;

        return $this->get('/deposits/closed', $parameters);
    }
}
