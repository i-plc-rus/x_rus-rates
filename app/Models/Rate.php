<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'external_id',
        'num_code',
        'char_code',
        'nominal',
        'name',
        'value',
        'v_unit_rate',
    ];
}
