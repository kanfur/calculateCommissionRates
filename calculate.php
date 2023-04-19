<?php

require_once 'vendor/autoload.php';

use Src\CommissionCalculator;
use Src\CsvReader;
use Src\CurrencyRate;


$filename = $argv[1];
$currencyRate = new CurrencyRate();
$exchangeRates = $currencyRate->fetchRatesFromApi();
$calculator = new CommissionCalculator($exchangeRates);

$operations = CsvReader::read($filename);
foreach ($operations as $operation) {
    $commissionFee = $calculator->calculate(
        $operation['userId'],
        $operation['userType'],
        $operation['operationType'],
        $operation['operationDate'],
        $operation['amount'],
        $operation['currency']
    );

    echo $commissionFee . PHP_EOL;
}