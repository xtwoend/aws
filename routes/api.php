<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AwsLoggerApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| AWS Logger API Routes
|--------------------------------------------------------------------------
|
| These routes handle incoming data from AWS Logger weather stations
|
*/

// AWS Logger Data Reception API
Route::prefix('aws-logger')->name('api.aws-logger.')->group(function () {
    // Receive single weather data record
    Route::post('/data', [AwsLoggerApiController::class, 'receiveData'])
        ->name('receive-data');
    
    // Receive bulk weather data records
    Route::post('/bulk-data', [AwsLoggerApiController::class, 'receiveBulkData'])
        ->name('receive-bulk-data');
    
    // Get device status and latest data
    Route::get('/device/{deviceId}/status', [AwsLoggerApiController::class, 'getDeviceStatus'])
        ->name('device-status');
});

/*
|--------------------------------------------------------------------------
| Public Health Check
|--------------------------------------------------------------------------
|
| Simple health check endpoint for monitoring
|
*/
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'AWS Dashboard API',
        'timestamp' => now()->toISOString(),
        'version' => '1.0.0'
    ]);
})->name('api.health');
