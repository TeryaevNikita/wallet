<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    /**
     * @var string
     */
    protected $table = 'operation';

    protected $fillable = ['type', 'owner_wallet_id', 'wallet_to_id', 'amount', 'currency' ];
}
