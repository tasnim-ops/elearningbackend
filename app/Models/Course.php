<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Teacher;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'course_description',
        'price',
        'category_id',
        'teacher_id',
        'documents',
    ];

    protected $casts = [
        'documents' => 'json',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
