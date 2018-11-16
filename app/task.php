<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class task extends Model
{
    protected $fillable = [
        'user_id', 'name_task', 'text_task', 'status_task',
    ];
}
