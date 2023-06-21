<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Teacher;
class Visioconference extends Model
{
    use HasFactory;
    protected $fillable = [
        'conf_title','conf_description','teacher_id','status','conf_date',
        'conf_time',

    ];


    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
