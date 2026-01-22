<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
// use App\Http\Controllers\Admin\EventController as AdminEventController; // Replaced by AdminController
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// User routes
Route::middleware(['auth'])->group(function () {
    // Redirect /dashboard to specific dashboards based on role is handled in dashboard.blade.php
    // checking for specific dashboard routes:
    Route::get('/customer/dashboard', [CustomerController::class, 'index'])->name('customer.dashboard');
    Route::get('/my-bookings', [CustomerController::class, 'bookings'])->name('customer.bookings'); // Added route for bookings
    Route::get('/bookings/{booking}/pay', [CustomerController::class, 'payBooking'])->name('bookings.pay'); // Added route for payment

    Route::resource('events', CustomerController::class)->only(['index', 'show']); // Use CustomerController for listing/showing events
    Route::resource('bookings', BookingController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('events/{event}/book', [CustomerController::class, 'book'])->name('events.book'); // Use CustomerController for booking
    Route::post('/vouchers/apply', [CustomerController::class, 'applyVoucher'])->name('vouchers.apply');
    Route::post('/payment/process', [CustomerController::class, 'processPayment'])->name('payment.process');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/history', [AdminController::class, 'history'])->name('history');
    Route::resource('events', AdminController::class); // Use global AdminController
});

require __DIR__.'/auth.php';
