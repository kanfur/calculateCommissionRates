<?php

use Src\CommissionCalculator;
use PHPUnit\Framework\TestCase;
use Src\CurrencyRate;

class CommissionCalculatorTest extends TestCase
{
    private $commissionCalculator;

    protected function setUp(): void
    {
        $payseraClient = new CurrencyRate();
        $exchangeRates = $payseraClient->getExchangeExampleRates();
        $this->commissionCalculator = new CommissionCalculator($exchangeRates);
    }

    public function testCalculateCommission()
    {
        $inputs = [
            [
                'operationDate' => '2014-12-31',
                'userId' => 4,
                'userType' => 'private',
                'operationType' => 'withdraw',
                'amount' => 1200.00,
                'currency' => 'EUR'
            ],
            [
                'operationDate' => '2015-01-01',
                'userId' => 4,
                'userType' => 'private',
                'operationType' => 'withdraw',
                'amount' => 1000.00,
                'currency' => 'EUR'
            ],
            [
                'operationDate' => '2016-01-05',
                'userId' => 4,
                'userType' => 'private',
                'operationType' => 'withdraw',
                'amount' => 1000.00,
                'currency' => 'EUR'
            ],
            [
                'operationDate' => '2016-01-06',
                'userId' => 2,
                'userType' => 'business',
                'operationType' => 'withdraw',
                'amount' => 300.00,
                'currency' => 'EUR'
            ],
            [
                'operationDate' => '2016-01-07',
                'userId' => 1,
                'userType' => 'private',
                'operationType' => 'deposit',
                'amount' => 200.00,
                'currency' => 'EUR'
            ],
            [
                'operationDate' => '2016-02-19',
                'userId' => 5,
                'userType' => 'private',
                'operationType' => 'withdraw',
                'amount' => 3000000,
                'currency' => 'JPY'
            ],
        ];

        $expectedResults = [
            '0.60',
            '3.00',
            '0.00',
            '1.50',
            '0.06',
            '8612',
        ];
        foreach ($inputs as $input) {
            $commissionFee = $this->commissionCalculator->calculate(
                $input['userId'],
                $input['userType'],
                $input['operationType'],
                $input['operationDate'],
                $input['amount'],
                $input['currency']
            );

            $actualResults[] = $commissionFee;
        }

        $this->assertEquals($expectedResults, $actualResults);
    }
}
