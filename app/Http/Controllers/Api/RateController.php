<?php

namespace App\Http\Controllers\Api;

use App\Contracts\IRateService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RateController extends BaseController
{
    public function currencies(Request $request)
    {
        return $this->success(
            [
                'currencies' => [
                    'rur' => 'Rubles',
                    'eur' => 'Euro',
                    'usd' => 'Dollar',
                ]
            ]
        );
    }

    public function rate(Request $request)
    {
        $validatedData = $request->validate([
            'currency' => 'required',
            'rate' => 'required|numeric',
            'date' => 'required|date|date_format:Y-d-m',
        ]);


        $currency = $validatedData['currency'];
        $rate = $validatedData['rate'];
        $dateStr = $validatedData['date'];

        $date = Carbon::parse($dateStr);

        $res = app(IRateService::class)->addRate($currency, $rate, $date);

        return $this->success([
            'message' => 'Success'
        ]);
    }
}
