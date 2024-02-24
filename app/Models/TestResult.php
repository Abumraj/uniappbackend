<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    public function Users()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function Tests()
    {
        return $this->belongsTo('App\Models\Test');
    }
}
