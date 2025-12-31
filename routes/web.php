<?php

use App\Http\Controllers\RiskController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RiskController::class, 'index'])->name('risk.index');
Route::post('/risks', [RiskController::class, 'store'])->name('risk.store');
Route::put('/risks/{risk}', [RiskController::class, 'update'])->name('risk.update');
Route::delete('/risks/{risk}', [RiskController::class, 'destroy'])->name('risk.destroy');
Route::get('/api/statistics', [RiskController::class, 'statistics'])->name('risk.statistics');
