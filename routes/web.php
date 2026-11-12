<?php

use App\Http\Controllers\AdminController;
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
    Route::post('/shipments', [ShipmentController::class, 'store'])->name('shipments.store');
    Route::get('/shipments/{shipment}', [ShipmentController::class, 'show'])->name('shipments.show');
    Route::post('/shipments/{shipment}/tracking', [ShipmentController::class, 'storeTrackingEvent'])->name('shipments.tracking.store');
    Route::post('/shipments/{shipment}/exceptions', [ShipmentController::class, 'storeException'])->name('shipments.exceptions.store');
    Route::patch('/exceptions/{exception}/resolve', [ShipmentController::class, 'resolveException'])->name('exceptions.resolve');

    // Modul Administrator (Slide 05: Kelola Carrier, Lokasi, dan Audit Log)
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/admin/carriers', [AdminController::class, 'storeCarrier'])->name('admin.carriers.store');
    Route::post('/admin/locations', [AdminController::class, 'storeLocation'])->name('admin.locations.store');

    // Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
