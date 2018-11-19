<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
         'project_id', 'name_task', 'text_task', 'status_task','deadline'
    ];

}
