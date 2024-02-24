<?php

namespace App\Models;
use App\Models\Storylike;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{

    const LEVEL_GENERAL=5;
    const LEVEL_FACULTY=4;
    const LEVEL_DEPARTMENT=3;
    const LEVEL_JUPEB=2;
    const LEVEL_REMEDIAL=1;
    const LEVEL_ASPIRANT=6;

    protected $fillable = [ 'user_id','name', 'type','description','level','image', 'text', 'video','background_color', 'status'];

    public function storylikes()
    {
        return $this->hasMany('App\Models\Storylike');
    }
    public function storyviews()
    {
        return $this->hasMany('App\Models\Storyview');
    }
    public function storydislikes()
    {
        return $this->hasMany('App\Models\Storydislike');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}
