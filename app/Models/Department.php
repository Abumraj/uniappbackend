<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    const UNDERGRADUATE_PLAN = 1;
    const REMEDIAL_PLAN = 2;
    const ASPIRANT_PLAN = 3;
    const POSTUTME_PLAN = 4;
    const JUPEB_PLAN = 5;
    const IJMB_PLAN = 6;
    const SANDWICH_PLAN = 7;

    protected $fillable = ['name', 'type', 'faculty_id'];

    public function Faculty()
    {
        return $this->belongsTo('App\Models\Faculty');
    }
    public function Courses()
    {
        return $this->belongsToMany('App\Models\Course');
    }
    public function Users()
    {
        return $this->hasMany('App\Models\User');
    }
}
