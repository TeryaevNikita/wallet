<?php

namespace App\Http\Controllers\Api;

use App\Contracts\IOperationService;
use App\Contracts\IWalletService;
use App\Models\Project\Client;
use App\Models\Project\Dto\NewOperation;
use Illuminate\Http\Request;

class OperationController extends BaseController
{
    public function withdraw(int $walletId, Request $request)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'amount' => 'required',
            'currency' => 'required',
        ]);

        $dataOperation = New NewOperation(array_merge($validatedData, [
            'owner_wallet_id' => $walletId
        ]));

        $operation = app(IOperationService::class)->create('withdraw', $dataOperation, true);

        $wallet = app(IWalletService::class)->getClientWalletByEmail($user->email);

        return $this->success([
            'message' => 'Success Withdraw',
            'amount' => $wallet->amount / 100,
        ]);
    }

    public function transfer(int $walletId, int $walletTo, Request $request)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'amount' => 'required',
            'currency' => 'required',
        ]);


        $dataOperation = New NewOperation(array_merge($validatedData, [
            'owner_wallet_id' => $walletId,
            'wallet_to_id' => $walletTo,
        ]));

        $operation = app(IOperationService::class)->create('transfer', $dataOperation, true);

        $wallet = app(IWalletService::class)->getClientWalletByEmail($user->email);

        return $this->success([
            'message' => 'Success Transfer',
            'amount' => $wallet->amount / 100,
        ]);
    }
}
