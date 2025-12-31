<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RiskController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Risk management (for all authenticated users)
    Route::get('/', [RiskController::class, 'index'])->name('risk.index');
    Route::post('/risks', [RiskController::class, 'store'])->name('risk.store');
    Route::put('/risks/{risk}', [RiskController::class, 'update'])->name('risk.update');
    Route::delete('/risks/{risk}', [RiskController::class, 'destroy'])->name('risk.destroy');
    Route::get('/api/statistics', [RiskController::class, 'statistics'])->name('risk.statistics');

    // Admin routes
    Route::middleware(AdminMiddleware::class)->prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
        Route::get('/risks', [AdminController::class, 'risks'])->name('admin.risks');
    });
});
