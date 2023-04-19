<?php

declare(strict_types=1);

namespace Src;

interface CurrencyRateInterface
{
    public function fetchRates(): array;
}
