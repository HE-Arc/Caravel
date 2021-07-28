<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthAPIController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\CommentController;

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
    Route::post('groups/{group}/members', [GroupController::class, 'join']);

    Route::group(['middleware' => 'check.group'], function () {

        Route::post('groups/{group}/files', [GroupController::class, 'upload'])->name('groups.upload');
        Route::get('groups/{group}/files/{file}', [GroupController::class, 'getFile'])->name('groups.files');

        Route::apiResources([
            'groups' => GroupController::class,
            'groups.tasks' => TaskController::class,
        ]);

        Route::apiResource('groups.subjects', SubjectController::class)->except(['show']);
        Route::apiResource('groups.questions', QuestionController::class)->except(['show', 'index']);
        Route::apiResource('groups.comments', CommentController::class)->except(['show', 'index']);

        Route::get('groups/{group}/members', [GroupController::class, 'members']);
        Route::patch('groups/{group}/members', [GroupController::class, 'updateMemberStatus']);
        Route::delete('groups/{group}/members', [GroupController::class, 'removeMember']);
        Route::patch('groups/{group}/reactions', [TaskController::class, 'updateReaction']);
        Route::patch('groups/{group}/finished', [TaskController::class, 'setFinished']);
    });

    Route::delete('profile/group', [UserController::class, 'removeGroup']);
    Route::get('profile/notifications', [ProfileController::class, 'getNotifications']);
    Route::post('profile/fcmToken', [ProfileController::class, 'registerFCMToken']);
    Route::delete('profile/fcmToken', [ProfileController::class, 'deleteFCMToken']);
    Route::post('profile/markAsRead', [ProfileController::class, 'markAsRead']);
    Route::patch('profile', [ProfileController::class, 'update']);
});
