<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    const IS_ACTIVE =1;
    protected $fillable = ['name', 'reference_id'];

}
