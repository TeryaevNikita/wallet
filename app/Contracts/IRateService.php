<?php

namespace App\Contracts;

use Carbon\Carbon;

interface IRateService
{
    public function getRates(array $currencies, Carbon $date = null): array;

    public function addRate(string $currency, float $rate, Carbon $date = null): bool;

}
