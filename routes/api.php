<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DeletedTaskController;
use App\Http\Controllers\CompletedTaskController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//  Public
Route::post('/signup', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//  Protected
Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/task_list/{id?}', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('/task/{id}', [TaskController::class, 'destroy']);

    Route::delete('/task_complete/{id}', [CompletedTaskController::class, 'complete']);
    Route::delete('/task_completed/{id}/complete', [CompletedTaskController::class, 'delete']);
    Route::get('/task_completed', [CompletedTaskController::class, 'index']); //Jan added
    Route::delete('/task_completed/complete_all', [CompletedTaskController::class, 'completeAll']);

    Route::get('/task_deleted', [DeletedTaskController::class, 'index']);
    Route::post('/task_deleted/{id}/restore', [DeletedTaskController::class, 'restore']);
    Route::delete('/task_deleted/{id}/delete', [DeletedTaskController::class, 'delete']);
    Route::post('/task_deleted/restore_all', [DeletedTaskController::class, 'restoreAll']);
    Route::delete('/task_deleted/delete_all', [DeletedTaskController::class, 'deleteAll']);

    Route::get('/image/{userId}', [ImageController::class, 'getImage'])->where('userId', '[0-9]+');
    Route::post('/upload/{userId}', [ImageController::class, 'upload']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
