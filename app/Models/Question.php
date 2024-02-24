<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['course_id', 'chapter_id', 'question', 'imageUrl', 'answer', 'option2', 'option3', 'option4', 'is_published','solution'];



    public function Chapters()
    {
        return $this->belongsTo('App\Models\Chapter');
    }
    public function Courses()
    {
        return $this->belongsTo('App\Models\Course');
    }





    public function Answer()
    {
        return $this->hasOne('App\Models\Answer');
    }


    public function Option()
    {
        return $this->hasOne('App\Models\Option');
    }
}
