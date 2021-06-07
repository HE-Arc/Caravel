<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthAPIController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [AuthAPIController::class, 'login'])->name('login');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('logout', [AuthAPIController::class, 'logout'])->name('logout');
    Route::post('groups/{group}/files', [GroupController::class, 'upload'])->name('groups.upload');
    Route::get('groups/{group}/files/{file}', [GroupController::class, 'getFile'])->name('groups.files');
    Route::post('groups/{group}/members', [GroupController::class, 'join']);

    Route::apiResources([
        'groups' => GroupController::class,
        'groups.tasks' => TaskController::class,
        'groups.subjects' => SubjectController::class,
        /*'groups.questions' => QuestionController::class,
        'groups.comment' => CommentController::class,*/
    ]);

    #members of groups (get, delete, no post as this is the role of the "join" mecanic)
    Route::get('groups/{group}/members', [GroupController::class, 'members']);
    Route::patch('groups/{group}/members', [GroupController::class, 'updateMemberStatus']);
    Route::delete('groups/{group}/members/{user}', [GroupController::class, 'removeMember']);

    Route::delete('profile/groups/{group}', [UserController::class, 'removeGroup']);
    Route::patch('profile', [ProfileController::class, 'update']);

});
