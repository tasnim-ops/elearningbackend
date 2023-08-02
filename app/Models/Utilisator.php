<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Utilisator extends Model
{
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


    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

}
