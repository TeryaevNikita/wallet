<?php

namespace App\Services;

use App\Contracts\IOperationService;
use App\Exceptions\ApiException;
use App\Models\DB\Operation as DBOperation;
use App\Models\DB\Transaction as DBTransaction;
use App\Models\DB\Wallet as DBWallet;
use App\Models\Project\Dto\NewOperation;
use App\Models\Project\Operations\BaseOperation;
use App\Models\Project\Operations\Transfer;
use App\Models\Project\Operations\Withdraw;
use App\Models\Project\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OperationDBService implements IOperationService
{

    public function create(string $type, NewOperation $dataOperation, bool $isExecute = false): BaseOperation
    {
        switch ($type) {
            case 'withdraw':
                $operation = new Withdraw();
                $operation->walletId = $dataOperation->owner_wallet_id;
                $operation->amount = $dataOperation->amount;
                $operation->currency = $dataOperation->currency;
                break;
            case 'transfer':
                $operation = new Transfer();
                $operation->walletId = $dataOperation->owner_wallet_id;
                $operation->walletToId = $dataOperation->wallet_to_id;
                $operation->amount = $dataOperation->amount;
                $operation->currency = $dataOperation->currency;
                break;
            default:
                throw new ApiException("Unknown type");
        }

        $dbOperation = new DBOperation([
            'type' => $type,
            'owner_wallet_id' => $operation->walletId,
            'wallet_to_id' => $operation->walletToId ?? null,
            'amount' => $operation->amount * 100,
            'currency' => $operation->currency,
        ]);

        $res = $dbOperation->save();

        $operation->id = $dbOperation->id;

        if (!$res) {
            throw new ApiException("Can't sabe");
        }


        if ($isExecute) {
            $res = $this->execute($operation);
            if (true !== $res) {
                throw new ApiException("Can't Execute transactions");
            }
        }

        return $operation;
    }

    public function execute(BaseOperation $operation): bool
    {
        $transactions = $operation->getTransactions();

        DB::beginTransaction();
        foreach ($transactions as $transaction) {
            if (!$transaction instanceof Transaction) {
                DB::rollBack();
                return false;
            }

            if ($transaction->getAmount() < 0) {
                //TODO: check wallet amount
            }

            $DBTransaction = new DBTransaction([
                'wallet_id' => $transaction->getWalletId(),
                'operation_id' => $transaction->getOperationId(),
                'amount' => floor($transaction->getAmount() * 100),
                'currency' => $transaction->getCurrency(),
            ]);

            $res = $DBTransaction->save();

            if (!$res) {
                DB::rollBack();
                return false;
            }

            DBWallet::where('id', $transaction->getWalletId())
                ->update([
                    'amount' => DB::raw('amount + ' . $transaction->getAmount() * 100),
                ]);
        }

        DB::commit();

        return true;
    }

    public function getTransactions(int $walletId, Carbon $dateFrom = null, Carbon $dateTo = null): array
    {
        $query = DB::table('wallet_transaction as wt')
            ->select(
                'wt.id as transId',
                'o.type as type',
                DB::raw("wt.amount / 100 as amount"),
                'wt.currency as currency',
                'wt.created_at as date',
                DB::raw("CASE WHEN wt.currency = 'usd' THEN wt.amount / 100
                    ELSE round(wt.amount / CAST(r.rate as numeric) / 100.0, 2)
                    END
                    as usdAmount")
            )
            ->leftjoin('operation as o', 'o.id', '=', 'wt.operation_id')
            ->leftJoin('rate as r', function ($join) {
                $join->on('wt.currency', '=', 'r.currency')
                    ->on(DB::raw("TO_CHAR(wt.created_at, 'YYYYMMDD')"), '=', DB::raw("TO_CHAR(r.date, 'YYYYMMDD')"));
            })
            ->where('wt.wallet_id', $walletId)
            ->orderBy('date');

        if ($dateFrom) {
            $query = $query->where('wt.created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query = $query->where('wt.created_at', '<=', $dateTo);
        }

        $result = $query->get();

        return $result->toArray();
    }
}
