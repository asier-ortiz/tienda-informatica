<?php

namespace Backend\API;

use Exception;

class CurrencyExchangeManager
{
    private static $conversionRates = null;

    private static function fetchCurrencyExchangesFor($currency): void
    {
        if (is_null(self::$conversionRates)) {
            $key = $_ENV['CURRENCY_EXCHANGE_KEY'];
            $url = "https://v6.exchangerate-API.com/v6/$key/latest/$currency";
            $response_json = file_get_contents($url);
            if (false !== $response_json)
                self::$conversionRates = json_decode($response_json)->conversion_rates;
        }
    }

    /**
     * @throws Exception
     */
    public static function convertEuroTo($currency, $amount): float
    {
        self::fetchCurrencyExchangesFor('EUR');

        if (is_null(self::$conversionRates))
            throw new Exception('Error al recuperar la información de divisas');
        else
            return round(($amount * self::$conversionRates->$currency), 2);
    }
}