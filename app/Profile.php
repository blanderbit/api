<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id', 'email', 'nickname','name', 'last_name', 'surname','number', 'confirm_email', 'country', 'city',
        'marital_status', 'number', 'photo', 'gender', 'age'
    ];
}
