<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AwsLoggerController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::get('/', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Dashboard data endpoint
    Route::get('/dashboard-data', [DashboardController::class, 'getData'])->name('dashboard.data');
    
    // User Management routes
    Route::resource('users', UserController::class);
    
    // AWS Logger routes
    Route::get('/aws-logger', [AwsLoggerController::class, 'index'])->name('aws-logger.index');
    Route::get('/aws-logger/{deviceId}', [AwsLoggerController::class, 'show'])->name('aws-logger.show');
    Route::get('/aws-logger/{deviceId}/data', [AwsLoggerController::class, 'getDataTablesData'])->name('aws-logger.datatables');
    Route::get('/aws-logger-data', [AwsLoggerController::class, 'getData'])->name('aws-logger.data');
    Route::get('/aws-logger-realtime', [AwsLoggerController::class, 'getRealTimeData'])->name('aws-logger.realtime');
    Route::get('/aws-logger-export', [AwsLoggerController::class, 'export'])->name('aws-logger.export');
    
    // API Testing page
    Route::get('/api-test', function () {
        return view('api-test');
    })->name('api-test');
    
    // Modal Testing page (for debugging)
    Route::get('/modal-test', function () {
        return view('modal-test');
    })->name('modal-test');
    
    // Device Management routes
    Route::resource('devices', DeviceController::class);
    Route::post('/devices/{device}/toggle-status', [DeviceController::class, 'toggleStatus'])->name('devices.toggle-status');
    Route::get('/devices-data', [DeviceController::class, 'getData'])->name('devices.data');
    Route::post('/devices/sync-aws-logger', [DeviceController::class, 'syncWithAwsLogger'])->name('devices.sync-aws-logger');
    Route::get('/devices-export', [DeviceController::class, 'export'])->name('devices.export');
});

require __DIR__.'/auth.php';
