<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\BookingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API Routes (no authentication required)
Route::get('/fields/search', [FieldController::class, 'apiSearch'])->name('api.fields.search');
Route::get('/fields/{field}/availability', [FieldController::class, 'getAvailability'])->name('api.fields.availability');
Route::post('/bookings/check-availability', [BookingController::class, 'checkAvailability'])->name('api.bookings.check-availability');

// Protected API Routes (require authentication)
Route::middleware('auth:sanctum')->group(function() {
    // Payment routes
    Route::get('/payments', [\App\Http\Controllers\API\PaymentController::class, 'index'])->name('api.payments.index');
    Route::get('/payments/{id}', [\App\Http\Controllers\API\PaymentController::class, 'show'])->name('api.payments.show');
    Route::get('/payment-methods', [\App\Http\Controllers\API\PaymentController::class, 'getPaymentMethods'])->name('api.payment-methods');
    
    // Booking routes
    Route::get('/bookings', [\App\Http\Controllers\API\BookingController::class, 'userBookings'])->name('api.bookings.user');
    Route::get('/bookings/{id}', [\App\Http\Controllers\API\BookingController::class, 'show'])->name('api.bookings.show');
    Route::post('/bookings', [\App\Http\Controllers\API\BookingController::class, 'store'])->name('api.bookings.store');
    Route::post('/bookings/{id}/cancel', [\App\Http\Controllers\API\BookingController::class, 'cancel'])->name('api.bookings.cancel');
});
