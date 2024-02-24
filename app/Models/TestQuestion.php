<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestQuestion extends Model
{
    protected $fillable = ['test_id', 'question', 'imageUrl', 'answer', 'option2', 'option3', 'option4'];



    public function Tests()
    {
        return $this->belongsTo('App\Models\Test');
    }
}
