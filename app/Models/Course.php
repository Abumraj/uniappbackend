<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Course extends Model
{
    const UNDERGRADUATE = 'stalite';
    const REMEDIAL_PLAN = 'remedial';
    const ASPIRANT_PLAN = 'aspirant';
    // const POSTUTME_PLAN = 4;
    const JUPEB_PLAN = 'jupeb';
    const IJMB_PLAN = 'ijmb';
    const SANDWICH_PLAN = 'sandwich';
    // use HasMediaTrait;

//     public function registerMediaCollections()
// {
//     $this->addMediaCollection('courseImage')->singleFile();
// }

    protected $fillable = ['id','name', 'code','description','semester','price', 'courseMaterialLink', 'courseChatLink', 'is_published', 'type','unit'];

    public function Chapters()
    {
        return $this->hasMany('App\Models\Chapter');
    }
    public function Tests()
    {
        return $this->hasMany('App\Models\Test');
    }
    public function Questions()
    {
        return $this->hasMany('App\Models\Question');
    }

    public function Videos()
    {
        return $this->hasMany('App\Models\Video');
    }


    public function Users()
    {
        return $this->belongsToMany('App\Models\User')->withPivot('role');
    }
    // public function Remedials()
    // {
    //     return $this->belongsToMany('App\Models\Models\Remedial');
    // }
    // public function Aspirants()
    // {
    //     return $this->belongsToMany('App\Models\Models\Aspirant');
    // }
    public function Departments()
    {
        return $this->belongsToMany('App\Models\Department');
    }

     public function Levels()
    {
        return $this->belongsTo('App\Models\Level');
    }


}
