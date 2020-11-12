<?php

use Illuminate\Support\Facades\Route;


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

Route::get('/', function () {
    return view('welcome');
});



Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
	Route::post('groups/{group}/upload', 'App\Http\Controllers\GroupController@upload')->name('groups.upload');
	Route::get('groups/{group}/files/{file}', 'App\Http\Controllers\GroupController@getFile')->name('groups.files');
	Route::post('groups/{group}/tasks/{task}/comment', 'App\Http\Controllers\TaskController@comment')->name('groups.tasks.comment.store');
	Route::delete('groups/{group}/tasks/{task}/comment/{comment}', 'App\Http\Controllers\TaskController@delComment')->name('groups.tasks.comment.delete');
	Route::delete('groups/{group}/tasks/{task}/attachement/{file}', 'App\Http\Controllers\TaskController@delAttachement')->name('groups.tasks.attachement.delete');
	Route::resource('groups', App\Http\Controllers\GroupController::class);
	Route::resource('groups.tasks', App\Http\Controllers\TaskController::class);
	Route::resource('groups.subjects', App\Http\Controllers\SubjectController::class);

	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::delete('profile', ['as' => 'profile.deletePicture', 'uses' => 'App\Http\Controllers\ProfileController@deletePicture']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
});