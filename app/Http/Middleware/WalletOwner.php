<?php

namespace App\Http\Middleware;

use App\Contracts\IWalletService;
use App\Exceptions\ApiException;
use Closure;
use Exception;

class WalletOwner
{
    public function handle($request, Closure $next)
    {
        $wallet_id = (int)$request->walletId ?? 0;
        $client_id = $request->user()->id ?? 0;

        /** @var IWalletService $walletService */
        $walletService = app(IWalletService::class);

        if ($wallet_id && $client_id && $walletService->HasClientWallet($client_id, $wallet_id)){
            return $next($request);
        }

        throw new ApiException("You are not owner of this wallet");
    }
}
