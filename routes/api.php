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

    Route::get('profile/{id}', 'ProfilsController@getProfile');
    Route::post('update_profile/{id}', 'ProfilsController@updateProfile');
    Route::delete('remove_profile/{id}', 'ProfilsController@removeProfile');

    Route::get('profile/{id}/tasks', 'TasksController@getTasksUser');
    Route::post('profile/{id}/tasks', 'TasksController@addTaskUser');
    Route::post('profile/{id}/update_task/{id_task}', 'TasksController@updateTasksUser');
    Route::delete('profile/{id}/remove_task/{id_task}', 'TasksController@removeTaskUser');

});
