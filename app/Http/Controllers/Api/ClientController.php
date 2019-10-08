<?php

namespace App\Http\Controllers\Api;

use App\Contracts\IClientService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClientController extends BaseController
{

    public function statistic(Request $request)
    {
        $validatedData = $request->validate([
            'client_email' => 'required',
            'date_from' => 'date|date_format:Y-d-m',
            'date_to' => 'date|date_format:Y-d-m',
        ]);

        $clientEmail = $validatedData['client_email'];
        $dateFromStr = $validatedData['date_from'] ?? null;
        $dateToStr = $validatedData['date_to'] ?? null;

        $dateFrom = $dateFromStr === null ? null : (Carbon::parse($dateFromStr))->startOfDay();
        $dateTo = $dateToStr === null ? null : (Carbon::parse($dateToStr))->endOfDay();

        $result = app(IClientService::class)->getStatistic($clientEmail, $dateFrom, $dateTo);

        return $this->success([
            'data' => $result,
        ]);
    }

    public function getRecipients(Request $request)
    {
        $user = $request->user();

        return $this->success([
            'data' => app(IClientService::class)->getRecipients($user->id),
        ]);
    }

    public function getClients(Request $request)
    {
        return $this->success([
            'data' => app(IClientService::class)->getRecipients(),
        ]);
    }
}
