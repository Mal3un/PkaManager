<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListPoint extends Model
{
    use HasFactory;
    protected $fillable = [
        'classe_id',
        'student_id',
        'session',
        'status',
        'note',
        'subject_id'
    ];
}
