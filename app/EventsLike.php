<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventsLike extends Model
{
    protected $fillable = [
        'events_id', 'id_user'
    ];

    protected $with = ['profile'];

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'id_user');
    }
}
