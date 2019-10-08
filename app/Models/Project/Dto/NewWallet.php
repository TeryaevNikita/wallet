<?php

namespace App\Models\Project\Dto;

use App\Models\Project\Client;
use Illuminate\Database\Eloquent\Model;

/**
 * Class NewWallet
 * @package App\Models\Dto
 * @property int amount
 * @property string currency
 */
class NewWallet extends Model
{
    /**
     * @var Client
     */
    private $client;

    protected $fillable = [
        'amount', 'currency'
    ];

    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }
}
