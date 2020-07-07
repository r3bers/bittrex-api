# bittrex-api
[![Build Status](https://travis-ci.com/r3bers/bittrex-api.svg?branch=master)](https://travis-ci.com/r3bers/bittrex-api)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/r3bers/bittrex-api/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/r3bers/bittrex-api/?branch=master)
[![Test Coverage](https://api.codeclimate.com/v1/badges/e82ddd9ab3f2c47beb16/test_coverage)](https://codeclimate.com/github/r3bers/bittrex-api/test_coverage)
[![Maintainability](https://api.codeclimate.com/v1/badges/e82ddd9ab3f2c47beb16/maintainability)](https://codeclimate.com/github/r3bers/bittrex-api/maintainability)
[![GitHub license](https://img.shields.io/github/license/r3bers/bittrex-api)](https://github.com/r3bers/bittrex-api/blob/master/LICENSE)
![Packagist](https://img.shields.io/packagist/dt/r3bers/bittrex-api)

A simple PHP wrapper for [Bittrex API v3](https://bittrex.github.io/api/v3). Bittrex is the next generation crypto trading platform.

## Requirements

* PHP >= 7.3
* ext-json
* [Bittrex account](https://global.bittrex.com/), API key and API secret

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
$ composer require r3bers/bittrex-api
```
or add

```json
"r3bers/bittrex-api" : "^1.0"
```

to require the section of your application's `composer.json` file.

## Basic usage

### Example
```php
use R3bers\BittrexApi\BittrexClient;

$client = new BittrexClient();
$client->setCredential('API_KEY', 'API_SECRET');

$data = $client->public()->getMarkets();
```
## Available methods

### Public API

#### Get the open and available trading markets
```php
$data = $client->public()->getMarkets();
```

#### Get all supported currencies
```php
$data = $client->public()->getCurrencies();
```

#### Get the current tick values for a market
```php
$data = $client->public()->getTicker('LTC-BTC');
```
#### Get the last 24 hour summary of all active exchanges
```php
$data = $client->public()->getMarketSummaries();
```

#### Get the last 24 hour summary of all active exchanges for a market
```php
$data = $client->public()->getMarketSummary('LTC-BTC');
```

#### Get the orderbook for a given market
```php
$data = $client->public()->getOrderBook('LTC-BTC');
```

#### Get latest trades that have occurred for a specific market
```php
$data = $client->public()->getMarketHistory('LTC-BTC');
```

### Market API

#### Place a buy order in a specific market
```php
$data = $client->market()->buyLimit('LTC-BTC', 1.2, 1.3);
```

#### Place a sell order in a specific market
```php
$data = $client->market()->sellLimit('LTC-BTC', 1.2, 1.3);
```

#### Cancel a buy or sell order
```php
$data = $client->market()->cancel('251c48e7-95d4-d53f-ad76-a7c6547b74ca9');
```

#### Get all orders that you currently have opened
```php
$data = $client->market()->getOpenOrders('LTC-BTC');
```

### Account API

#### Get all balances from your account
```php
$data = $client->account()->getBalances();
```

#### Get balance from your account for a specific currency
```php
$data = $client->account()->getBalance('BTC');
```

#### Get or generate an address for a specific currency
```php
$data = $client->account()->getDepositAddress('BTC');
$data = $client->account()->setDepositAddress('BTC');
```

#### Withdraw funds from your account
```php
$data = $client->account()->withdraw('BTC', 20.40, 'EAC_ADDRESS');
```

#### Get a single order by uuid
```php
$data = $client->account()->getOrder('251c48e7-95d4-d53f-ad76-a7c6547b74ca9');
```

#### Get order history
```php
$data = $client->account()->getOrderHistory('LTC-BTC');
```

#### Get withdrawal history
```php
$data = $client->account()->getWithdrawalHistory('BTC');
```

#### Get deposit history
```php
$data = $client->account()->getDepositHistory('BTC');
```

## Further Information
Please, check the [Bittrex site](https://bittrex.github.io/api/v3) documentation for further
information about API.

## License

`r3bers/bittrex-api` is released under the MIT License. See the bundled [LICENSE](./LICENSE) for details.
