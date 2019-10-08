<?php


namespace App\Models\Project\Dto;

use Illuminate\Database\Eloquent\Model;

/**
 * Class NewClient
 * @package App\Models\Dto
 * @property string name
 * @property string email
 * @property string password
 * @property string country
 * @property string city
 * @property string wallet_cur
 */
class NewClient extends Model
{
    protected $fillable = [
        'name', 'email', 'password', 'country', 'city', 'wallet_cur',
    ];
}
