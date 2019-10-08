<?php

namespace App\Models\Project\Operations;

use App\Exceptions\ApiException;
use App\Models\Project\Transaction;

class Transfer extends BaseOperation
{
    /**
     * @return array
     * @throws ApiException
     */
    function getTransactions(): array
    {
        $transactionFrom = New Transaction();
        $transactionTo = New Transaction();

        $transactionFrom
            ->setOperationId($this->id)
            ->setAmount($this->walletId, -1.0 * $this->amount, $this->currency);

        $transactionTo
            ->setOperationId($this->id)
            ->setAmount($this->walletToId, $this->amount, $this->currency);

        return [$transactionFrom, $transactionTo];
    }
}
