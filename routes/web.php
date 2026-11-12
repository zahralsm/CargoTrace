<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShipmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard Utama
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// Manajemen Shipment & Pelacakan (Slide 07, 08, 09)
Route::middleware('auth')->group(function () {
    Route::get('/shipments/{shipment}', [ShipmentController::class, 'show'])->name('shipments.show');
    Route::post('/shipments/{shipment}/tracking', [ShipmentController::class, 'storeTrackingEvent'])->name('shipments.tracking.store');
    Route::post('/shipments/{shipment}/exceptions', [ShipmentController::class, 'storeException'])->name('shipments.exceptions.store');
    Route::patch('/exceptions/{exception}/resolve', [ShipmentController::class, 'resolveException'])->name('exceptions.resolve');

    // Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';