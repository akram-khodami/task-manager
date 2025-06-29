<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\V1\FolderController;
use App\Http\Controllers\V1\ProjectController;
use App\Http\Controllers\V1\RoleController;
use App\Http\Controllers\V1\TaskController;
use App\Http\Controllers\V1\UserController;

use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->middleware('guest');;
    Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout'])->middleware('auth');;
    Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user'])->middleware('auth');;
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('users/availableRoles', [UserController::class, 'availableRoles']);
    Route::apiResource('users', UserController::class);
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('folders', FolderController::class);
    Route::apiResource('tasks', TaskController::class);
});
