<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Live extends Model
{
    protected $fillable = ['title', 'course_id', 'subtitle', 'live_url', 'chatLink', 'type', 'status', 'start_at'];



    public function Course()
    {
        return $this->belongsTo('App\Models\Course');
    }
}
