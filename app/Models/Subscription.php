<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    const UNDERGRADUATE_PLAN = 1;
    const REMEDIAL_PLAN = 2;
    const ASPIRANT_PLAN = 3;
    const POSTUTME_PLAN = 4;
    const JUPEB_PLAN = 5;
    const IJMB_PLAN = 6;
    const SANDWICH_PLAN = 7;


    protected $fillable = ['user_id', 'name', 'paystack_id', 'paystack_plan', 'paystack_code', 'quantity', 'trial_ends_at', 'ends_at'];


    public function User()
    {
        return $this->hasMany('App\Models\User');
    }
}
