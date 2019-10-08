<?php

namespace App\Contracts;

use App\Models\Project\Client;
use App\Models\Project\Dto\NewWallet;
use App\Models\Project\Wallet;

interface IWalletService
{
    public function create(NewWallet $walletData): Wallet;

    public function hasClientWallet(string $client_id, int $wallet_id): bool;

    public function getClientWalletByEmail(string $clientEmail): ?Wallet;
}
