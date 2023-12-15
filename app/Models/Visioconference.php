<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Teacher;
use Laravel\Sanctum\HasApiTokens;
class Visioconference extends Model
{
    use HasFactory;
    use HasApiTokens;
    protected $fillable = [
        'title','description','teacher_id','conftime','confdate',
        'participants','duration',

    ];
    protected $casts = [
        'participants' => 'json',
    ];
    protected $with=['teacher'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
