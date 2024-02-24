<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audio extends Model
{
    use HasFactory;
    protected $fillable = ['chapter_id', 'name', 'description','duration','imageurl', 'created_by', 'updated_by', 'status','url'];

    public function Chapters()
    {
        return $this->belongsTo('App\Models\Chapter');
    }

}
