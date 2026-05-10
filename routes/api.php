<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Middleware\CheckAdmin;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::controller(TaskController::class)->prefix('task')->group(function () {
        Route::get('', 'index');
        Route::post('', 'store');
        Route::post('reorder', 'reorder');
        Route::patch('mark-as-complete/{id}', 'markAsComplete');
    });

    Route::middleware(CheckAdmin::class)->group(function () {
        Route::controller(AdminController::class)->prefix('admin')->group(function () {
            Route::get('/dashboard', 'dashboard');
            Route::get('/users', 'users');
            Route::get('/tasks', 'tasks');
            Route::delete('/task/{id}', 'deleteTask');
            Route::get('/task/get-per-user/{userId}', 'getTasksPerUser');
        });
    });
});
