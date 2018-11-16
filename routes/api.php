<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------|
*/

Route::post('login', 'AuthController@login');
Route::post('register', 'AuthController@signup');

Route::group([
    'middleware' => ['auth:api']
], function() {
    Route::get('logout', 'AuthController@logout');

    Route::post('profile', 'ProfilsController@getProfile');
    Route::put('update_profile', 'ProfilsController@updateProfile');


    Route::put('currentUser', 'AuthController@updateCurrentUser');

    Route::get('users', 'UserController@getUsers');
    Route::get('users/{id}', 'UserController@getUser')->where('id', '[0-9]+');

});
