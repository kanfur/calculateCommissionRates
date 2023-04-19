<?php

namespace Src;

class CommissionCalculator
{
    private $exchangeRates;
    private $withdrawals;

    public function __construct($exchangeRates)
    {
        $this->exchangeRates = $exchangeRates;
        $this->withdrawals = [];
    }

    public function calculate($userId, $userType, $operationType, $operationDate, $amount, $currency)
    {
        if ($operationType === 'deposit') {
            return $this->calculateDepositFee($amount, $currency);
        }

        if ($userType === 'private') {
            return $this->calculatePrivateWithdrawFee($userId, $operationDate, $amount, $currency);
        }

        if ($userType === 'business') {
            return $this->calculateBusinessWithdrawFee($amount, $currency);
        }

        return 0;
    }

    private function calculateDepositFee($amount, $currency)
    {
        $feePercentage = 0.0003;
        $fee = $amount * $feePercentage;
        return $this->roundUp($fee, $currency);
    }

    private function calculatePrivateWithdrawFee($userId, $operationDate, $amount, $currency)
    {
        $feePercentage = 0.003;
        $weeklyLimitAmount = 1000;
        $weeklyLimitOperations = 3;

        if (!isset($this->withdrawals[$userId])) {
            $this->withdrawals[$userId] = [];
        }

        $weekStartDate = date("Y-m-d", strtotime("last monday", strtotime($operationDate)));
        if (!isset($this->withdrawals[$userId][$weekStartDate])) {
            $this->withdrawals[$userId][$weekStartDate] = [
                'operations' => 0,
                'amount' => 0,
            ];
        }

        $userWithdrawal = &$this->withdrawals[$userId][$weekStartDate];

        $userWithdrawal['operations']++;
        $amountInEur = $currency === 'EUR' ? $amount : $amount / $this->exchangeRates[$currency];

        if ($userWithdrawal['operations'] <= $weeklyLimitOperations && $userWithdrawal['amount'] < $weeklyLimitAmount) {
            $remainingFreeAmount = $weeklyLimitAmount - $userWithdrawal['amount'];
            $freeAmount = min($remainingFreeAmount, $amountInEur);

            $userWithdrawal['amount'] += $freeAmount;
            $amountInEur -= $freeAmount;
        }

        if ($currency !== 'EUR') {
            $amountInEur *= $this->exchangeRates[$currency];
        }

        $fee = $amountInEur * $feePercentage;
        return $this->roundUp($fee, $currency);
    }

    private function calculateBusinessWithdrawFee($amount, $currency)
    {
        $feePercentage = 0.005;
        $fee = $amount * $feePercentage;
        return $this->roundUp($fee, $currency);
    }

    private function roundUp($value, $currency)
    {
        $precision = 2;
        if ($currency === 'JPY') {
            $precision = 0;
        }

        $roundedValue = ceil($value * pow(10, $precision)) / pow(10, $precision);

        // Format the result to show 2 decimals for non-JPY currencies
        if ($currency !== 'JPY') {
            $roundedValue = number_format($roundedValue, 2, '.', '');
        }

        return $roundedValue;
    }
}
