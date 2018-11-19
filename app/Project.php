<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'user_id', 'project_name', 'color', 'status_task'
    ];
}
