<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Utilisator;
class Teacher extends Utilisator
{
    use HasFactory;
    protected $table = 'teachers';

}
