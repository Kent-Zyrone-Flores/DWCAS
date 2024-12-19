<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingpageController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\AuthController;
use App\Models\Appointment;



// Public Routes (Accessible to Guests)
Route::middleware('guest')->group(function () {
    Route::get('/landingpage', function () { 
        return view('landingpage'); 
    })->name('landingpage');
    
    Route::post('/landingpage', [LandingpageController::class, 'landingpage']);

    Route::get('signup', [SignupController::class, 'signup'])->name('signup');
    Route::post('signup', [SignupController::class, 'save'])->name('signup.save');

    Route::get('login', [LoginController::class, 'login'])->name('login');
    Route::post('login', [LoginController::class, 'submit'])->name('login.submit');
});

// Protected Routes for Authenticated Users
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('user', [AppointmentController::class, 'user'])->name('user');
    Route::post('user', [AppointmentController::class, 'submit'])->name('user.submit');
    
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::post('/appointments/update-status/{id}', [AppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
    Route::get('/store-report', [ReportsController::class, 'storeReport'])->name('store_report');
    Route::post('/store-report', [ReportsController::class, 'storeReport']);
    Route::get('/history', [HistoryController::class, 'index'])->name('history');
    Route::get('/history/filter', [HistoryController::class, 'filter'])->name('history.filter');
});

Route::post('/appointments/{id}/upload', [AppointmentController::class, 'upload'])->name('appointments.upload');
Route::post('/upload-image', [AppointmentController::class, 'upload'])->name('image.upload');
Route::post('/upload-proof', [AppointmentController::class, 'uploadProof'])->name('upload.proof');
Route::delete('/appointments/{id}', [AppointmentController::class, 'delete'])->name('appointments.delete');
Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments');
Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
Route::post('/appointments/update-status/{id}', [AppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');
Route::delete('/appointments/{id}', [AppointmentController::class, 'deleteAppointment'])->name('appointments.delete');
Route::post('/appointments/update-status/{id}', [AppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');
Route::get('/check-availability', [AppointmentController::class, 'checkAvailability'])->name('appointments.checkAvailability');
Route::get('/booked-slots', [AppointmentController::class, 'bookedSlots'])->name('appointments.bookedSlots');
Route::post('/appointments/reschedule/{id}', [AppointmentController::class, 'reschedule'])->name('appointments.reschedule');
Route::post('/appointments/reschedule', [AppointmentController::class, 'reschedule'])->name('appointments.reschedule');
Route::post('appointments/{id}/delete', [AppointmentController::class, 'delete'])->name('appointments.delete');
Route::post('/appointments/update-status', [AppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');