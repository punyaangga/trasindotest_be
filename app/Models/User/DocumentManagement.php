<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentManagement extends Model
{
    use HasFactory;

    protected $fillable = [
        'dm_title',
        'dm_content',
        'dm_signing',
    ];
}
