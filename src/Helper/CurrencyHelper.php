<?php

namespace App\Helper;

use Exchanger\Exchanger;
use Exchanger\ExchangeRateQuery;
use Exchanger\Contract\ExchangeRateProvider;
use Http\Client\Curl\Client;

/**
 * Utility class for converting currency using live exchange rates.
 */
class CurrencyHelper
{
    protected static ?ExchangeRateProvider $provider = null;

    /**
     * Converts an amount from one currency to another using real-time exchange rates.
     *
     * @param float  $amount Amount to convert.
     * @param string $from   Source currency code (e.g., "GBP").
     * @param string $to     Target currency code (e.g., "EUR").
     *
     * @return float Converted amount.
     */
    public static function convert(float $amount, string $from, string $to): float
    {
        if ($from === $to) {
            return $amount;
        }

        $provider = self::getProvider();
        $rate = $provider->getExchangeRate(new ExchangeRateQuery("{$from}/{$to}"));
        return round($amount * $rate->getValue(), 2);
    }

    /**
     * Lazily instantiate and reuse the exchange rate provider.
     *
     * @return ExchangeRateProvider
     */
    protected static function getProvider(): ExchangeRateProvider
    {
        if (!self::$provider) {
            self::$provider = Exchanger::create(
                ['provider' => 'exchangeratesapi'],
                new Client()
            );
        }

        return self::$provider;
    }
}
