<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;
    const LEVEL_100 = 100;
    const LEVEL_200 = 200;
    const LEVEL_300 = 300;
    const LEVEL_400 = 400;
    const LEVEL_500 = 500;
    const LEVEL_600 = 600;
    const LEVEL_700 = 700;
    const LEVEL_800 = 800;
    protected $fillable = ['id','name', 'level'];


    public function user()
      {
    return $this->hasMany('App\Models\User');

      }
    public function course()
      {
    return $this->hasMany('App\Models\Course');

      }

}
