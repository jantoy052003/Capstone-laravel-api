<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DeletedTaskController;


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

//Public Routes
// Route::post('/signup', [AuthController::class, 'signup']);
// Route::post('/login', [AuthController::class, 'login']);

// Route::get('/tasks', [TaskController::class, 'index']);
// Route::get('/tasks/search', [TaskController::class, 'search']); //may heirarchy pala tong mga lokong to
// Route::get('/tasks/{id}', [TaskController::class, 'show']);
// Route::get('/image/{image}', [ImageController::class, 'getImage'])->where('image', '.*');

//Protected Routes(Login required)
// Route::group(['middleware' => ['auth:sanctum']], function() {
//     Route::post('/tasks', [TaskController::class, 'store']);
//     Route::put('/tasks/{id}', [TaskController::class, 'update']);
//     Route::delete('tasks/{id}', [TaskController::class, 'destroy']);
//     Route::post('/logout', [AuthController::class, 'logout']);
//     Route::post('/upload', [ImageController::class, 'upload']);
//     Route::put('/users/profile', [UserController::class, 'updateProfilePicture']);
//     Route::get('/tasks/user/{id}', [TaskController::class, 'getUserTasks']);
// });

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

    Route::get('/task_deleted', [DeletedTaskController::class, 'index']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
