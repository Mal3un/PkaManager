<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'image',
        'link',
        'status',
    ];

    public function getDateConverted(){
        $date = date("d-m-Y", strtotime($this->created_at));
        return $date;
    }

    public function getTimeConverted()
    {
        $time = date("H:i", strtotime($this->created_at));
        return $time;
    }
}
