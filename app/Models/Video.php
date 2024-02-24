<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = ['chapter_id', 'name', 'description', 'url'];

    public function Chapters()
    {
        return $this->belongsTo('App\Models\Chapter');
    }

}
