<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/scan');
});

// Auth Routes
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/scan', [ScanController::class, 'index'])->name('scan.index');
    Route::post('/api/scan', [ScanController::class, 'store'])->name('scan.store');
    Route::get('/api/scan/history', [ScanController::class, 'history'])->name('scan.history');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/export', function () {
        return "Fitur Export Excel akan menggunakan Maatwebsite/Laravel-Excel. Controller belum di-generate di langkah ini.";
    })->name('export');
});
