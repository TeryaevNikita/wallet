<?php

namespace App\Models\Project\Operations;

use App\Exceptions\ApiException;
use App\Models\Project\Transaction;

class Withdraw extends BaseOperation
{
    /**
     * @return array
     * @throws ApiException
     */
    function getTransactions(): array
    {
        $transaction = New Transaction();

        $transaction
            ->setOperationId($this->id)
            ->setAmount($this->walletId, $this->amount, $this->currency);

        return [$transaction];
    }
}
