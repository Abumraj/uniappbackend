<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    public function TestQuestions()
    {
        return $this->hasMany('App\Models\TestQuestion');
    }
    public function TestResults()
    {
        return $this->hasMany('App\Models\TestResult');
    }

    public function Course()
    {
        return $this->belongsTo('App\Models\Course');
    }
    public function Users()
    {
        return $this->belongsToMany('App\Models\User')->withTimestamps()->withPivot('is_approved');
    }
}
