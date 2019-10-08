<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * @var string
     */
    protected $table = 'wallet_transaction';

    /**
     * @var array
     */
    protected $fillable = [
        'wallet_id', 'operation_id', 'amount', 'currency',
    ];

}
