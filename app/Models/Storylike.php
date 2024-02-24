<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storylike extends Model
{
     use HasFactory;
    protected $fillable = [ 'user_id','story_id'];

    public function Stories()
    {
        return $this->belongsTo('App\Models\Story');
    }

}
