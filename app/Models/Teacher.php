<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Utilisator;
use Laravel\Sanctum\HasApiTokens;
class Teacher extends Utilisator
{
    use HasFactory;
    use HasApiTokens;
    protected $table = 'teachers';

}
