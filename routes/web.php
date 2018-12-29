<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/{any}', 'AppController@index')->where('any', '.*');
//Route::get('to_google', 'AuthController@toGoogle');
Route::get('to_google', 'AuthController@login_google');
Route::get('login/socialite/{type}', 'Socialise@redirectToProvider');
Route::get('login/google/callback/{type}', 'Socialise@handleProviderCallback');
