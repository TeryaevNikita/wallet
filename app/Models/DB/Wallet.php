<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{

    /**
     * @var string
     */
    protected $table = 'wallet';

    protected $fillable = [
        'amount', 'currency', 'user_id'
    ];
}
