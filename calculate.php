<?php

require_once 'vendor/autoload.php';

use Src\CommissionCalculator;
use Src\CsvReader;
use Src\FurkanCurrencyRate;


$filename = $argv[1];
$currencyRate = new FurkanCurrencyRate();
$exchangeRates = $currencyRate->fetchRates();
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