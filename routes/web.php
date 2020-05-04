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

Route::get('/test', function () {
    return view('welcome');
});

Route::get('/probando/{name}', 'SaludosController@index');
Route::get('/mostrarclientes', 'SaludosController@verTodos');

Auth::routes(['verify' => true]);

Route::redirect('/', '/home');

Route::view('scan', 'scan');


Route::get('/home', 'HomeController@index')->name('home');
Route::get('threads/create', 'ThreadController@create');
Route::get('threads/search', 'SearchController@show');
Route::get('threads/{channel}/{thread}', 'ThreadController@show');
Route::delete('threads/{channel}/{thread}', 'ThreadController@destroy');
Route::patch('threads/{channel}/{thread}', 'ThreadController@update');
Route::post('threads', 'ThreadController@store')->middleware('verified:threads');


Route::post('locked-threads/{thread}', 'LockedThreadsController@store')->name('locked-threads.store')->middleware('admin');
Route::delete('locked-threads/{thread}', 'LockedThreadsController@destroy')->name('locked-threads.destroy')->middleware('admin');

//Route::resource('threads', 'ThreadController');
Route::get('/threads/{channel}/{thread}/replies', 'ReplyController@index');
Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store');
Route::patch('replies/{reply}', 'ReplyController@update');
Route::delete('replies/{reply}', 'ReplyController@destroy')->name('replies.destroy');

Route::get('threads/{channel?}', 'ThreadController@index')->name('threads');
//Route::get('threads', 'ThreadController@index' );
Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@store')->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@destroy')->middleware('auth');

Route::post('replies/{reply}/favorites', 'FavoritesController@store');
Route::delete('replies/{reply}/favorites', 'FavoritesController@destroy');

Route::get('/register/confirm', 'Auth\VerificationController@index')->name('register.confirm');

Route::get('/profiles/{user}', 'ProfilesController@show');
Route::get('/profiles/{user}/notifications', 'userNotificationsController@index');
Route::delete('/profiles/{user}/notifications/{notifications}', 'userNotificationsController@destroy');

Route::post('/replies/{reply}/best', 'BestRepliescontroller@store')->name('best-replies.store');

Route::get('api/user', 'UserController@index');
Route::post('api/user/{user}/avatar', 'UserController@store')->middleware('auth')->name('avatar');
