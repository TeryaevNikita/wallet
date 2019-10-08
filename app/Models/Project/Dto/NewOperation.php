<?php


namespace App\Models\Project\Dto;


use Illuminate\Database\Eloquent\Model;

/**
 * Class NewClient
 * @package App\Models\Dto
 * @property string type
 * @property int owner_wallet_id
 * @property int wallet_to_id
 * @property int amount
 * @property string currency
 */
class NewOperation extends Model
{
    protected $fillable = [
        'owner_wallet_id', 'wallet_to_id', 'amount', 'currency'
    ];
}
