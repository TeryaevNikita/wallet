<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    /**
     * @var string
     */
    protected $table = 'location';

    /**
     * @var array
     */
    protected $fillable = [
        'country', 'city', 'user_id'
    ];

}
