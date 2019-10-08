<?php

namespace App\Services;

use App\Contracts\IRateService;
use App\Exceptions\ApiException;
use App\Models\DB\Rate as DBRate;
use Carbon\Carbon;

class RateDBService implements IRateService
{
    public function getRates(array $currencies, Carbon $date = null): array
    {
        $date = $date ?? Carbon::now();

        $rates = DBRate::whereIn('currency', $currencies)
            ->whereBetween('date', [$date->startOfDay(), $date->copy()->endOfDay()])
            ->pluck('rate', 'currency');

        return array_merge($rates->toArray(), ['usd' => 1]);
    }

    public function addRate(string $currency, float $rateValue, Carbon $date = null): bool
    {
        $date = $date ?? Carbon::now();

        $rate = DBRate::where('currency', $currency)
            ->whereBetween('date', [$date->startOfDay(), $date->copy()->endOfDay()])
            ->first();

        if (null === $rate) {
            $rate = new DBRate([
                'currency' => $currency,
                'date' => $date,
            ]);
        }

        $rate->rate = $rateValue;

        $res = $rate->save();

        if ($res !== true) {
            throw new ApiException("Can't save");
        }

        return true;
    }
}
