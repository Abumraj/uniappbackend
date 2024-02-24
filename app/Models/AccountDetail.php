<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AccountDetail extends Model
{
    protected $fillable = ['accontName', 'accountNumber', 'bankName', 'user_id'];



    public function Users()
    {
        return $this->belongsTo('App\Models\Users');
    }
}
