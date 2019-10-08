<?php

namespace App\Contracts;

use App\Models\Project\Dto\NewClient;
use App\Models\Project\Client;
use Carbon\Carbon;

interface IClientService
{
    public function create(NewClient $clientData): Client;

    public function getStatistic(string $clientEmail, Carbon $dateFrom, Carbon $dateTo): array;
}
