<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = ['question_id', 'option2', 'option3', 'option4'];





    public function Question()
    {
        return $this->belongsTo('App\Models\Question');
    }
}
