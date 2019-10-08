<?php


namespace App\Contracts;


use App\Models\Project\Dto\NewOperation;
use App\Models\Project\Operations\BaseOperation;
use Carbon\Carbon;

interface IOperationService
{
    public function create(string $type, NewOperation $dataOperation, bool $isExecute = false): BaseOperation;

    public function execute(BaseOperation $operation);

    public function getTransactions(int $walletId, Carbon $dateFrom = null, Carbon $dateTo = null): array;
}
