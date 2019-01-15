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



Route::get('country', 'ProfilsController@getCountry');


Route::group([
    'middleware' => ['auth:api']
], function() {
    Route::get('logout', 'AuthController@logout');
    Route::post('test', 'AuthController@test');
    Route::get('profile/{id}', 'ProfilsController@getProfile');

    Route::post('update_profile/{id}', 'ProfilsController@updateProfile');
    Route::delete('remove_profile/{id}', 'ProfilsController@removeProfile');

    Route::get('get_events', 'EventsController@getEvents');
    Route::get('get_event/{id}', 'EventsController@getOneEvent');
    Route::get('profile/{id}/get_events', 'EventsController@getUserEvents');
    Route::get('profile/{id}/get_event/{id_event}', 'EventsController@getUserOneEvent');
    Route::post('profile/{id}/update_event/{id_event}', 'EventsController@updateUserOneEvent');
    Route::post('profile/{id}/remove_event/{id_event}', 'EventsController@removeUserOneEvent');
    Route::post('profile/{id}/add_events', 'EventsController@addEvent');

    Route::post('event/toggle_like_for_event/{id_event}', 'EventLike@toggleLikeForEvents');
    Route::get('event/{id_event}/get_likes', 'EventLike@getLikeForEvents');

    Route::post('profile/add_comment_for_event/{id_event}', 'CommentController@addCommentForEvent');
    Route::post('profile/update_comment_for_event/{id_comments}', 'CommentController@updateCommentForEvent');
    Route::post('profile/remove_comment_for_event/{id_comments}', 'CommentController@removeCommentForEvent');
    Route::get('profile/get_comment_for_event/{id_event}', 'CommentController@getCommentsForEvent');
    Route::get('profile/get_comment_for_event/{id_event}/{id_comment}', 'CommentController@getOneCommentForEvent');


//    Route::post('profile/{id}/update_project/{id_project}', 'ProjectController@updateProjectUser');
//    Route::delete('profile/{id}/remove_project/{id_project}', 'ProjectController@removeProjectUser');

//    Route::get('profile/{id}/{id_project}/tasks', 'TasksController@getTaskProjectUser');
//    Route::post('profile/{id}/{id_project}/add_task', 'TasksController@addTaskProjectUser');
//    Route::post('profile/{id}/{id_project}/update_task/{id_task}', 'TasksController@updateTaskProjectUser');
//    Route::delete('profile/{id}/{id_project}/remove_task/{id_task}', 'TasksController@removeTaskProjectUser');

    Route::post('fileUpload', 'ProfilsController@fileUpload');

});
