<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'course_id',
        'major_id',
        'class_type',
        'teacher_id',
        'subject_id',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }



    public function getCourseNameAttribute()
    {
        return Course::find($this->course_id)->name;
    }
    public function getMajorNameAttribute()
    {
        return Major::find($this->major_id)->name;
    }


}
