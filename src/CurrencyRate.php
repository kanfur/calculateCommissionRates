<?php

namespace Src;

use Exception;

class CurrencyRate
{
    public function getExchangeExampleRates(): array
    {
        return [
            'USD' => 1.1497,
            'JPY' => 129.53,
        ];
    }
    public function fetchRatesFromApi()
    {
        $apiUrl = 'https://developers.paysera.com/tasks/api/currency-exchange-rates';
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
