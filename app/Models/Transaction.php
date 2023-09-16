<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'mtw_id',
        'user_id',
        'tr_qty',
        'tr_qty_unit',
        'tr_total_price',
    ];
}
