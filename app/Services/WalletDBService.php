<?php

namespace App\Services;

use App\Contracts\IWalletService;
use App\Exceptions\ApiException;
use App\Models\Project\Dto\NewWallet;
use App\Models\Project\Wallet;
use App\Models\DB\Wallet as DBWallet;

class WalletDBService implements IWalletService
{
    public function create(NewWallet $walletData): Wallet
    {
        $walletDb = new DBWallet([
            'amount' => $walletData->amount,
            'currency' => $walletData->currency,
        ]);

        $client = $walletData->getClient();

        if (null === $client) {
            throw new ApiException("Can't find client");
        }

        $walletDb->setAttribute('user_id', $client->getId());

        $res = $walletDb->save();

        if (!$res) {
            throw new ApiException("can't save wallet");
        }

        $wallet = New Wallet();

        $wallet->setAmount($walletDb->amount)
            ->setCurrency($walletDb->currency)
            ->setId($walletDb->id);

        return $wallet;
    }

    public function hasClientWallet(string $clientId, int $walletId): bool
    {
        return DBWallet::where(['id' => $walletId,
            'user_id' => $clientId,
        ])->exists();
    }

    public function getClientWalletByEmail(string $clientEmail): ?Wallet
    {

        $walletDb = DBWallet::select(
            'wallet.id as id',
            'wallet.amount as amount',
            'wallet.currency as currency'
        )
            ->join('users', 'users.id', '=', 'wallet.user_id')
            ->where('users.email', $clientEmail)
            ->first();

        if ($walletDb instanceof DBWallet) {
            $wallet = new Wallet();

            $wallet->setAmount($walletDb->amount)
                ->setCurrency($walletDb->currency)
                ->setId($walletDb->id);
        }

        return $wallet ?? null;
    }

    public function getCurrency(int $walletId): string
    {
        $wallet = DBWallet::select('currency')->where('id', $walletId)
            ->first();

        return strtolower($wallet->currency);
    }
}
