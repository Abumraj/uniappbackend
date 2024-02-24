<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class program extends Model
{
    protected $fillable = ['name', 'code'];

    public function schools()
    {
        return $this->hasMany('App\Models\school');
    }

}
