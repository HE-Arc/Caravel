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

//get filtered group API
Route::get('groups/create', ['as' => 'groups.create', 'uses' => 'App\Http\Controllers\GroupController@create']);
Route::get('groups/filtered/{string}', ['as' => 'groups.filtered', 'uses' => 'App\Http\Controllers\GroupController@filtered']);
Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
	Route::post('groups/{group}/upload', 'App\Http\Controllers\GroupController@upload')->name('groups.upload');
	Route::get('groups/{group}/files/{file}', 'App\Http\Controllers\GroupController@getFile')->name('groups.files');
	Route::post('groups/{group}/tasks/{task}/comment', 'App\Http\Controllers\TaskController@comment')->name('groups.tasks.comment.store');
	Route::delete('groups/{group}/tasks/{task}/comment/{comment}', 'App\Http\Controllers\TaskController@delComment')->name('groups.tasks.comment.delete');
	Route::delete('groups/{group}/tasks/{task}/attachement/{file}', 'App\Http\Controllers\TaskController@delAttachement')->name('groups.tasks.attachement.delete');
	Route::resource('groups', App\Http\Controllers\GroupController::class)->except(['create']);
	Route::resource('groups.tasks', App\Http\Controllers\TaskController::class);
	Route::resource('groups.subjects', App\Http\Controllers\SubjectController::class);

	#members of groups (get, delete, no post as this is the role of the "join" mecanic)
	Route::get('groups/{group}/members', ['as' => 'groups.members', 'uses' => 'App\Http\Controllers\GroupController@members']);
	Route::delete('groups/{group}/members/{user}', ['as' => 'groups.members.delete', 'uses' => 'App\Http\Controllers\GroupController@deleteMember']);
	#change leader
	Route::put('groups/{group}/leader/{user}', ['as' => 'groups.members.leader', 'uses' => 'App\Http\Controllers\GroupController@changeLeader']);
	#join a group, see the pending members, process "accept/refuse" a pending member
	Route::post('groups/{group}/join', ['as' => 'groups.join', 'uses' => 'App\Http\Controllers\GroupController@join']);
	Route::get('groups/{group}/pending', ['as' => 'groups.pending', 'uses' => 'App\Http\Controllers\GroupController@pending']);
	Route::patch('groups/{group}/pending/{user}/{status}', ['as' => 'groups.pending.process', 'uses' => 'App\Http\Controllers\GroupController@processPending']);

	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
});