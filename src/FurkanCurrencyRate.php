<?php

declare(strict_types=1);

namespace Src;

use Exception;

class FurkanCurrencyRate implements CurrencyRateInterface
{
    private const API_URL = 'https://developers.paysera.com/tasks/api/currency-exchange-rates';

    public function fetchRates(): array
    {
        $apiUrl = self::API_URL;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        if (!isset($data['rates'])) {
            throw new Exception('Error fetching rates from API');
        }

        return $data['rates'];
    }
}
