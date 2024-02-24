<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    protected $filllable = ['name'];

   public function Departments()
   {
       return $this->hasMany('App\Models\Department');
   }
}
