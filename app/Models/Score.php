<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Score extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'listpoint_score',
        'midterm_score',
        'lastterm_score',
        'note',
        'rank',
        'subject_id',
        'classe_id'
    ];


    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public function getStudentName(){
        $text =  $this->student->first_name . ' ' . $this->student->last_name;
        return $text;
    }
    public function getDateConverted(){
        $date = date("d-m-Y", strtotime($this->student->birthdate));
        return $date;
    }
    public function totalScore(){
        $total = ($this->listpoint_score * 0.1) + ($this->midterm_score * 0.3) + ($this->lastterm_score * 0.6);
        return $total;
    }
    public function getRankByScore($score){
        if($score >= 9) {
            return 'A+';
        }
        if($score >= 8.5){
            return 'A';
        }
        if($score >= 8){
            return 'B+';
        }
        if($score >= 7.0){
            return 'B';
        }
        if($score >= 6.5){
            return 'C+';
        }
        if($score >= 5.5){
            return 'C';
        }
        if($score >= 5.0){
            return 'D+';
        }
        if($score >= 4.0){
            return 'D';
        }
        return 'F';
    }





}
