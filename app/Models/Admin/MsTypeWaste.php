<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsTypeWaste extends Model
{
    use HasFactory;
    protected $fillable = [
        'mtw_name',
        'mtw_description',
        'mtw_photo',
        'mtw_price',
        'mtw_unit',
    ];
}
