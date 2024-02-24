<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable
{
    use  HasApiTokens, HasFactory, HasRoles, Notifiable;
    const CURRENT_SEMESTER = 1;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'phone',
        'fcode',
        'imageurl',
        'matric_number',
        'status',
        'subscription_id',
        'usertype_id',
        'is_admitted',
        'is_login_first',
        'level_id',
        'title',
        'department_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function Courses()
    {
        return $this->belongsToMany('App\Models\Course')->withPivot('role', 'is_full_access');
    }
    public function Tests()
    {
        return $this->belongsToMany('App\Models\Test')->withTimestamps();
    }

    public function Chapters()
   {
       return $this->hasMany('App\Models\Chapter');
   }

   public function TestResult()
    {
        return $this->hasMany('App\Models\TestResult');
    }
   public function Notification()
    {
        return $this->hasMany('App\Models\Notification');
    }

     public function Department()
    {
        return $this->belongsTo('App\Models\Department');
    }

//    public function registerMediaCollections()
//    {
//        $this->addMediaCollection('profileImage');
//    }


   public function Stories()
   {
    return $this->hasMany('App\Models\Story');


   }
   public function Storyview()
   {
    return $this->hasMany('App\Models\Storyview');
   }
   public function Subscriptions()
   {
    return $this->belongsTo('App\Models\Subscription');
   }
   public function levels()
   {
    return $this->belongsTo('App\Models\Level');
   }
}
