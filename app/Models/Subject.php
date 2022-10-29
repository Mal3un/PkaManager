<?php

namespace App\Models;

use App\Enums\ClassTypeEnum;
use App\Enums\StudyTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subject extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'major_id',
        'number_credits',
        'study_type'
    ];

    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }
    public function getStudyTypeNameAttribute()
    {
        return StudyTypeEnum::getKeys($this->study_type)[0];
    }
    public function getMajorNameAttribute()
    {
        return Major::find($this->major_id)->name;
    }
}
