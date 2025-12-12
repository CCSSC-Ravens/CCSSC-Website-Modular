<?php

use App\Modules\Admin\Http\Controllers\AuthController;
use App\Modules\Admin\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix(config('admin.slug'))
    ->middleware('web')
    ->group(function () {
        Route::middleware('guest:admin')->group(function () {
            Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
            Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
        });

        Route::middleware('auth:admin')->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
            Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
        });
    });

