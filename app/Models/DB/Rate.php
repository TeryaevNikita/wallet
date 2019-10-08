<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    /**
     * @var string
     */
    protected $table = 'rate';

    protected $fillable = ['currency', 'rate', 'date'];

}
