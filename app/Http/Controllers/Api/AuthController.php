<?php

namespace App\Http\Controllers\Api;

use App\Contracts\IClientService;
use App\Contracts\IWalletService;
use App\Models\Project\Client;
use App\Models\Project\Dto\NewClient;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'email',
            'password' => 'required',
            'country' => 'required',
            'city' => 'required',
            'wallet_cur' => 'required',
        ]);

        $clientData = new NewClient($validatedData);

        $client = app(IClientService::class)->create($clientData);

        if (!$client instanceof Client) {
            return $this->error('ERROR created user!');
        }

        return $this->success(["message" => 'Successfully created user!']);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);

        $user = $request->user();

        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;

        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);

        $token->save();

        $wallet = app(IWalletService::class)->getClientWalletByEmail($user->email);

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'name' => $user->name,
            'main_wallet_id' => $wallet->id,
            'amount' => $wallet->amount/100,
            'currency' => $wallet->currency,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return $this->success([
            'success' =>'logout_success'
        ]);
    }
}
