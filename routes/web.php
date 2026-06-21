<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MotorController;
use App\Http\Controllers\TransactionController;

// Public Routes
Route::get('/', [HomeController::class, 'index']);
Route::get('/catalog', [MotorController::class, 'catalog']);
Route::get('/detail/{id}', [MotorController::class, 'detail']);

// Car Routes
Route::get('/cars', [App\Http\Controllers\CarController::class, 'catalog']);
Route::get('/cars/{id}', [App\Http\Controllers\CarController::class, 'detail']);

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->middleware('auth');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    Route::get('/history', [TransactionController::class, 'history'])->name('history');
    Route::post('/transaction/rent', [TransactionController::class, 'rent']);
    Route::post('/transaction/purchase', [TransactionController::class, 'purchase']);

    Route::post('/transaction/{transaction}/pay', [TransactionController::class, 'pay']);
    Route::post('/transaction/{transaction}/cancel', [TransactionController::class, 'cancel']);
    Route::post('/transaction/{transaction}/complete', [TransactionController::class, 'complete']);
});