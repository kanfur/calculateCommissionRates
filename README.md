# Calculate Commission Rates

Command to run: `php calculate.php input.csv`

Command to test with the data given below: `./vendor/bin/phpunit`

`2014-12-31,4,private,withdraw,1200.00,EUR`

`2015-01-01,4,private,withdraw,1000.00,EUR`

`2016-01-05,4,private,withdraw,1000.00,EUR`

`2016-01-06,2,business,withdraw,300.00,EUR`

`2016-01-05,1,private,deposit,200.00,EUR`

`2016-02-19,5,private,withdraw,3000000,JPY`

`ExchangeRate = [
'USD' => 1.1497,
'JPY' => 129.53,
]`

There are 5 class files in the project including unittest file

- CommissionCalculator.php (calculation service)
- CsvReader.php (reading input file)
- CurrencyRateInterface.php
- FurkanCurrencyRate.php (for ClientApi)
- CommissionCalculateTest.php (Testing)

### UnitTest
It performs a calculation test in CommissionCalculator.

I created a method that returns currency rates in test class.

I didn't use FurkanCurrencyRate Client class that fetching the rates from api in My Test class because it gives different results from the example results given in the requirements. For example: it returns '8608' instead of '8612' when some currencies return different values.
