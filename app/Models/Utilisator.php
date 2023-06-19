<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Utilisator extends Model
{
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'telephone',
        'password',
        'photo',
    ];


    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

}
