<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'subject_id',
        'day_exam',
        'time_start',
        'room',
        'shift_exam',
        'major_id',
        'course_id',
    ];


    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }


    public function getNameSubject()
    {
        return $this->subject->name;
    }

    public function getDateConverted(){
        $date = date("d-m-Y", strtotime($this->day_exam));
        return $date;
    }
    public function getRoomExam()
    {
        $room = 'Phòng:  ' . $this->room . ' - Ca thi:  ' . $this->shift_exam;
        return $room;
    }
    public function getTimeStart()
    {
        $time = date("H:i", strtotime($this->time_start));
        return $time;
    }
}
