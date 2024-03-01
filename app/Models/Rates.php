<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Rates
 * @package App\Models
 */
class Rates extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'num_code',
        'char_code',
        'nominal',
        'name',
        'value',
        'unit_rate',
        'date',
    ];
}
