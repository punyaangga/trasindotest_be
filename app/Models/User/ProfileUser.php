<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'pu_company_name',
        'pu_division',
        'pu_photo',
    ];
}
