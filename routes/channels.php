<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

//Broadcast::channel('App.User.{id}', function ($user, $id) {
//    return (int) $user->id === (int) $id;
//});

Broadcast::channel('chat.{chat_id}', function ($user, $chat_id) {
//    return (int) $user->chat_id === (int) $id;
//    dd($chat_id,$user);/
    return response()->json(\Illuminate\Support\Facades\Auth::user(), 401);
//    return false;
});
