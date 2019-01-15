<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'short', 'full_rus', 'full_eng'
    ];
}
