<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Teacher;
use Laravel\Sanctum\HasApiTokens;
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
    //every element can be called with teacher or category function (foreign key)
    protected $with=['category','teacher'];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
