<?php

namespace App\Providers;

use App\Contracts\IClientService;
use App\Contracts\IOperationService;
use App\Contracts\IRateService;
use App\Contracts\IWalletService;
use App\Services\ClientDBService;
use App\Services\OperationDBService;
use App\Services\RateDBService;
use App\Services\WalletDBService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(IClientService::class, ClientDBService::class);
        $this->app->singleton(IWalletService::class, WalletDBService::class);
        $this->app->singleton(IOperationService::class, OperationDBService::class);
        $this->app->singleton(IRateService::class, RateDBService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
