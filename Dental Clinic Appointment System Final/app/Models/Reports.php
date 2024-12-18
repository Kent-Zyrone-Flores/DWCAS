<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reports extends Model
{
    protected $fillable = [
        'name',
        'service',
        'subservice',
        'amount',
        'status',
        'date',
        'description',
    ];
}
