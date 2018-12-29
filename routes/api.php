<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------|
*/
//Route::post('home', 'AuthController@login');
Route::post('login', 'AuthController@login');
Route::get('login/socialite/{type}', 'Socialise@redirectToProvider');
Route::get('login/socialite/callback/{type}', 'Socialise@handleProviderCallback');

Route::post('register', 'AuthController@signup');

Route::group([
    'middleware' => ['auth:api']
], function() {
    Route::get('logout', 'AuthController@logout');

    Route::get('profile/{id}', 'ProfilsController@getProfile');
    Route::post('update_profile/{id}', 'ProfilsController@updateProfile');
    Route::delete('remove_profile/{id}', 'ProfilsController@removeProfile');

    Route::get('profile/{id}/get_projects', 'ProjectController@getProjectsUser');
    Route::get('profile/{id}/get_project/{id_project}', 'ProjectController@getProjectUser');
    Route::post('profile/{id}/add_project', 'ProjectController@addProjectUser');
    Route::post('profile/{id}/update_project/{id_project}', 'ProjectController@updateProjectUser');
    Route::delete('profile/{id}/remove_project/{id_project}', 'ProjectController@removeProjectUser');

    Route::get('profile/{id}/{id_project}/tasks', 'TasksController@getTaskProjectUser');
    Route::post('profile/{id}/{id_project}/add_task', 'TasksController@addTaskProjectUser');
    Route::post('profile/{id}/{id_project}/update_task/{id_task}', 'TasksController@updateTaskProjectUser');
    Route::delete('profile/{id}/{id_project}/remove_task/{id_task}', 'TasksController@removeTaskProjectUser');
});
