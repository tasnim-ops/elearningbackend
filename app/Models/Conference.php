<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Teacher;
class Conference extends Model
{
    use HasFactory;
    protected $fillable = [
        'date','titel','description','teacher_id','stutus',

    ];

    public function rules()
    {
        return [
            'status' => ['required', Rule::in(['to do', 'done'])],
            // Autres règles de validation pour les autres champs...
        ];
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
