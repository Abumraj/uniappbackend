<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Chapter extends Model
{
    use HasFactory;

    // public function registerMediaConversions(Media $media = null)
    // {
    //     $this->addMediaConversion('thumb')
    //           ->width(368)
    //           ->height(232)
    //           ->sharpen(10)
    //           ->performOnCollections('videos');
    // }

    // public function registerMediaCollections()
    // {
    //     $this->addMediaCollection('chapterImage')->singleFile();
    //     $this->addMediaCollection('videos');
    //     $this->addMediaCollection('cloud_videos')->useDisk('digitalocean');

    // }
    public function Questions()
    {
        return $this->hasMany('App\Models\Question');
    }



    public function Course()
    {
        return $this->belongsTo('App\Models\Course');
    }
    public function Users()
    {
        return $this->belongsTo('App\Models\Users');
    }
    public function Audios()
    {
        return $this->belongsTo('App\Models\Audios');
    }
    // public function Users()
    // {
    //     return $this->belongsTo('App\Models\Users');
    // }

}
