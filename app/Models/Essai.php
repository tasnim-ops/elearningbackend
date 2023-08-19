<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Essai extends Model
{
    use HasFactory;
    protected $fillable=[
        'essai_name',
        'essai_desc',
        'essai_result',
        'photo'
    ];
}
