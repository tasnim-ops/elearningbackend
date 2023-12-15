<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
class Student extends Model
{
    use HasFactory;
    use HasApiTokens;
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'phone',
        'password',
        'photo',
        'fb',
        'linkedin',
        'github',
        'desc'
    ];

}
