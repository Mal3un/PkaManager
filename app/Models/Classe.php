<?php

namespace App\Models;

use App\Enums\ClassTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Classe extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'major_id',
        'course_id',
        'class_type',
        'teacher_id',
        'start_date',
        'end_date',
        'subject_id',
        'created_by',
        'updated_by',
        'all_session',
        'schedule',
    ];
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
    public function getClassTypeNameAttribute()
    {
        return ClassTypeEnum::getKeys($this->class_type)[0];
    }
}
