<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Event extends Model
{
    protected $fillable = [
        'event_name', 'about_event', 'deadline', 'location', 'link', 'member'
    ];
//
    protected $with = ['profile'];
    protected $withCount = ['comment','likes'];
    protected $appends = ['like_user_event'];
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'user_id');
    }
    public function likes()
    {
        return $this->hasMany(EventsLike::class, 'events_id', 'id');
    }
    public function getLikeUserEventAttribute()
    {
        return $this->hasMany(EventsLike::class, 'events_id', 'id')
            ->where('id_user', Auth::user()->getAuthIdentifier())->first();

    }
//    public function getCountRentAttribute()
//    {
//        return $this->rent()->count();
//    }
//
//
//    public function getCountCommentAttribute()
//    {
//        return $this->comment()->count();
//    }
//    public  function rent()
//    {
//        return $this->hasMany(Rent::class);
//    }
    public function comment()
    {
        return $this->hasMany(Comment::class);
    }
//    public function profile()
//    {
//        return $this->hasOne(Profile::class);
//    }
//    public function profile()
//    {
//        return $this->hasMany(Profile::class);
//    }
//    public function comment()
//    {
//        return $this->hasMany(Comment::class);
//    }
}
