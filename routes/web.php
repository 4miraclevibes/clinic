<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DoctorScheduleController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User routes
    Route::resource('users', UserController::class);

    // Patient routes
    Route::resource('patients', PatientController::class);

    // Doctor routes
    Route::resource('doctors', DoctorController::class);

    // Schedule routes
    Route::resource('schedules', DoctorScheduleController::class);

    // Registration routes
    Route::resource('registrations', RegistrationController::class);
    Route::get('/get-doctors-by-date', [RegistrationController::class, 'getDoctorsByDate'])->name('registrations.getDoctorsByDate');

    // Transaction routes
    Route::resource('transactions', TransactionController::class);
    Route::get('/transactions/{transaction}/show', [TransactionController::class, 'show'])->name('transactions.show');
});

require __DIR__.'/auth.php';
