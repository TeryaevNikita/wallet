<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');


Route::get('currencies', 'Api\RateController@currencies');
Route::post('rate', 'Api\RateController@rate');
Route::post('statistic', 'Api\ClientController@statistic');
Route::get('clients', 'Api\ClientController@getClients');


Route::group([
    'middleware' => 'auth:api'
], function ($route) {
    Route::get('user', 'Api\AuthController@user');
    Route::get('recipients', 'Api\ClientController@getRecipients');
    Route::get('logout', 'Api\AuthController@logout');

    $route->group(['prefix' => 'wallet', 'middleware' => 'wallet.owner'], function ($route) {
        $route->post('/{walletId}/withdraw', 'Api\OperationController@withdraw')->where('walletId', '[0-9]+');
        $route->post('/{walletId}/transfer/{walletTo}', 'Api\OperationController@transfer')
            ->where([
                'walletId' =>'[0-9]+',
                'walletTo' =>'[0-9]+',
            ])
        ;
    });
});
