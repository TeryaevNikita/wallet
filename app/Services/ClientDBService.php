<?php

namespace App\Services;

use App\Contracts\IClientService;
use App\Contracts\IOperationService;
use App\Contracts\IWalletService;
use App\Exceptions\ApiException;
use App\Models\DB\Location;
use App\Models\Project\Dto\NewClient;
use App\Models\Project\Client;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ClientDBService implements IClientService
{
    public function create(NewClient $clientData): Client
    {
        $user = new User([
            'name' => $clientData->name,
            'email' => $clientData->email,
            'password' => bcrypt($clientData->password)
        ]);

        $res = $user->save();
        if (!$res) {
            throw new ApiException("Cant save");
        }

        $user->markEmailAsVerified();

        $client = New Client();

        $client->setId($user->id)
            ->setName($user->name);

        $this->addLocation($client, $clientData->country, $clientData->city);

        $client->addWallet(0, $clientData->wallet_cur);

        return $client;
    }

    protected function addLocation(Client $client, string $country, string $city): bool
    {
        $location = new Location([
            'country' => $country,
            'city' => $city,
            'user_id' => $client->getId(),
        ]);

        return $location->save();
    }

    public function getStatistic(string $clientEmail, Carbon $dateFrom = null, Carbon $dateTo = null): array
    {
        $wallet = app(IWalletService::class)->getClientWalletByEmail($clientEmail);

        if (null === $wallet) {
            throw new ApiException("Wallet not found");
        }

        $transactions = app(IOperationService::class)->getTransactions($wallet->id, $dateFrom, $dateTo);

        return $transactions;
    }

    public function getRecipients(int $clientId = null): array
    {
        $query = DB::table('users as u')
            ->select(
                'u.name as name',
                'u.email as client_email',
                'w.id as wallet_id'
            )
            ->leftJoin('wallet as w', 'u.id', '=', 'w.user_id');

        if ($clientId) {
            $query = $query->where('u.id', '!=', $clientId);
        }

        $data = $query->get();

        return $data->toArray();
    }
}
