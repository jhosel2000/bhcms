<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Availability API routes
Route::prefix('availability')->group(function () {
    Route::get('/check-slot', [App\Http\Controllers\Api\AvailabilityController::class, 'checkSlotAvailability']);
    Route::get('/provider-schedule', [App\Http\Controllers\Api\AvailabilityController::class, 'getProviderSchedule']);
    Route::get('/doctor/{doctor}/slots', [App\Http\Controllers\Api\AvailabilityController::class, 'getDoctorSlots']);
    Route::get('/midwife/{midwife}/slots', [App\Http\Controllers\Api\AvailabilityController::class, 'getMidwifeSlots']);
    Route::get('/bhw/{bhw}/slots', [App\Http\Controllers\Api\AvailabilityController::class, 'getBHWSlots']);
});

// Additional availability routes for better compatibility
Route::prefix('availability')->group(function () {
    Route::post('/check-availability', [App\Http\Controllers\Api\AvailabilityController::class, 'checkSlotAvailability']);
    Route::get('/slots/{providerType}/{providerId}', [App\Http\Controllers\Api\AvailabilityController::class, 'getSlotsByType']);
});

// Appointments API routes
Route::middleware('auth:sanctum')->prefix('doctor/appointments')->group(function () {
    Route::get('/calendar', [App\Http\Controllers\Api\AppointmentsController::class, 'calendar']);
});

// EHR API routes
// Route::middleware('auth:sanctum')->prefix('ehr')->group(function () {
//     Route::get('/patients/{patient}/records', [App\Http\Controllers\Api\EhrController::class, 'getPatientRecords']);

//     Route::get('/patients/{patient}/records/{record}', [App\Http\Controllers\Api\EhrController::class, 'getRecord']);
//     Route::put('/patients/{patient}/records/{record}', [App\Http\Controllers\Api\EhrController::class, 'updateRecord']);

//     Route::get('/patients/{patient}/statistics', [App\Http\Controllers\Api\EhrController::class, 'getPatientStatistics']);
//     Route::get('/record-types', [App\Http\Controllers\Api\EhrController::class, 'getRecordTypes']);
//     Route::get('/patients/{patient}/export', [App\Http\Controllers\Api\EhrController::class, 'exportRecords']);
// });


