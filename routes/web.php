<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Owner\OwnerController;
use App\Http\Controllers\PaymentController;

// Static pages
Route::get('/', function() {
    return view('home');
})->name('home');
Route::view('/products', 'products')->name('products.index');
Route::view('/about',    'pages.about')->name('about');
Route::view('/contact',  'pages.contact')->name('contact');
Route::view('/faq',      'pages.faq')->name('faq');
Route::view('/terms',    'pages.terms')->name('terms');
Route::view('/privacy',  'pages.privacy')->name('privacy');
Route::view('/owner/register', 'owner.register')->name('owner.register');
Route::view('/owner/register/success', 'owner.registration-success')->name('owner.register.success');

// Debug route to check admin access
Route::get('/admin-test', function() {
    if (!auth()->check()) {
        return response()->json(['error' => 'Not authenticated']);
    }
    
    $user = auth()->user();
    return response()->json([
        'user_id' => $user->id,
        'user_role' => $user->role,
        'is_admin' => $user->isAdmin(),
        'can_access_admin' => $user->role === 'admin'
    ]);
})->name('admin.test');

// Maintenance & Not Found
Route::view('/maintenance', 'pages.maintenance')->name('maintenance');
Route::view('/notfound',    'pages.notfound')->name('notfound');
Route::get('/bookings/not-found', [PaymentController::class, 'showNotFound'])->name('bookings.not-found');

// Auth routes
Auth::routes();

// Frontend fields
Route::get('/fields',                [FieldController::class, 'index'])->name('fields.index');
Route::get('/fields/search',         [FieldController::class, 'search'])->name('fields.search');
Route::get('/fields/create',         [FieldController::class, 'create'])->name('fields.create');

// Apply secure uploads middleware to routes that handle file uploads
Route::middleware(['auth', \App\Http\Middleware\SecureFileUploads::class])->group(function() {
    Route::post('/fields',               [FieldController::class, 'store'])->name('fields.store');
    Route::put('/fields/{field}',        [FieldController::class, 'update'])->name('fields.update');
});

Route::get('/fields/{field}/edit',   [FieldController::class, 'edit'])->name('fields.edit');
Route::delete('/fields/{field}',     [FieldController::class, 'destroy'])->name('fields.destroy');
Route::get('/fields/{field}',        [FieldController::class, 'show'])->name('fields.show');

// User routes
Route::middleware(['auth'])->group(function () {
    Route::get('/bookings',                [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/payments',       [App\Http\Controllers\PaymentController::class, 'listPayments'])->name('bookings.payments');
    Route::get('/bookings/create/{field}', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings',               [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])
        ->name('bookings.edit')
        ->missing(function () {
            return redirect()->route('bookings.not-found');
        });
    Route::put('/bookings/{booking}',      [BookingController::class, 'update'])
        ->name('bookings.update')
        ->missing(function () {
            return redirect()->route('bookings.not-found');
        });
    Route::post('/bookings/{booking}/confirm',[BookingController::class, 'confirm'])
        ->name('bookings.confirm')
        ->missing(function () {
            return redirect()->route('bookings.not-found');
        });
    Route::post('/bookings/{booking}/cancel',[BookingController::class, 'cancel'])
        ->name('bookings.cancel')
        ->missing(function () {
            return redirect()->route('bookings.not-found');
        });
    Route::get('/bookings/{booking}',       [BookingController::class, 'show'])
        ->name('bookings.show')
        ->missing(function () {
            return redirect()->route('bookings.not-found');
        });
    // Apply a custom missing model handler to these routes
    Route::get('/bookings/{booking}/payment', [App\Http\Controllers\PaymentController::class, 'show'])
        ->name('bookings.payment')
        ->missing(function () {
            return redirect()->route('bookings.not-found');
        });
    Route::post('/bookings/{booking}/payment', [App\Http\Controllers\PaymentController::class, 'process'])
        ->name('bookings.payment.process')
        ->missing(function () {
            return redirect()->route('bookings.not-found');
        });
    Route::get('/bookings/{booking}/payment/status', [App\Http\Controllers\PaymentController::class, 'status'])
        ->name('bookings.payment.status')
        ->missing(function () {
            return redirect()->route('bookings.not-found');
        });
    Route::post('/bookings/{booking}/payment/update-status', [App\Http\Controllers\PaymentController::class, 'updatePaymentStatus'])
        ->name('bookings.payment.update-status')
        ->missing(function () {
            return redirect()->route('bookings.not-found');
        });
    // Redirect old success/failed routes to status page
    Route::get('/bookings/{booking}/payment/success', [App\Http\Controllers\PaymentController::class, 'redirectToStatus'])
        ->name('bookings.payment.success');
    Route::get('/bookings/{booking}/payment/failed', [App\Http\Controllers\PaymentController::class, 'redirectToStatus'])
        ->name('bookings.payment.failed');
    Route::get('/payment/check-status', [App\Http\Controllers\PaymentController::class, 'checkStatus'])
        ->name('payment.check-status');
    
    // Debug route for payment access issues
    Route::get('/debug/payment/{booking}', function ($bookingId) {
        $booking = App\Models\Booking::with(['user', 'field'])->find($bookingId);
        
        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }
        
        if ($booking->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Check payment policy
        $canPay = Auth::user()->can('pay', $booking);
        
        return response()->json([
            'booking_id' => $booking->id,
            'user_id' => $booking->user_id,
            'current_user_id' => Auth::id(),
            'status' => $booking->status,
            'payment_status' => $booking->payment_status,
            'can_pay_policy' => $canPay,
            'field_exists' => $booking->field ? true : false,
            'field_name' => $booking->field->name ?? 'No field',
            'created_at' => $booking->created_at,
            'payment_url' => route('bookings.payment', $booking),
            'debug_info' => [
                'is_owner' => $booking->user_id === Auth::id(),
                'is_unpaid' => $booking->payment_status === 'unpaid',
                'is_not_cancelled' => !in_array($booking->status, ['cancelled', 'completed']),
                'policy_conditions' => [
                    'user_match' => $booking->user_id === Auth::id(),
                    'payment_unpaid' => $booking->payment_status === 'unpaid',
                    'status_allowed' => !in_array($booking->status, ['cancelled', 'completed']),
                ]
            ]
        ]);
    })->name('debug.payment');
});

// Midtrans webhook notification (outside auth middleware)
Route::post('/midtrans/notification', [App\Http\Controllers\PaymentController::class, 'notification'])->name('midtrans.notification');

// User routes continued
Route::middleware(['auth'])->group(function () {
    Route::get('/reviews',                  [App\Http\Controllers\ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/create/{field}',   [App\Http\Controllers\ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews',                 [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{review}/edit',    [App\Http\Controllers\ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}',         [App\Http\Controllers\ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}',      [App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // Booking-based review routes
    Route::get('/reviews/create-from-booking/{booking}', [App\Http\Controllers\ReviewController::class, 'createFromBooking'])->name('reviews.create-from-booking');
    Route::get('/reviews/reviewable-bookings', [App\Http\Controllers\ReviewController::class, 'reviewableBookings'])->name('reviews.reviewable-bookings');
    Route::get('/reviews/field/{field}',    [App\Http\Controllers\ReviewController::class, 'fieldReviews'])->name('reviews.field-reviews');

    // User profile
    Route::prefix('user')->group(function () {
        Route::get('/dashboard',        [\App\Http\Controllers\User\UserController::class, 'dashboard'])->name('user.dashboard');
        Route::get('/profile',          [\App\Http\Controllers\User\UserController::class, 'profile'])->name('user.profile');
        
        // Apply secure uploads middleware to routes that handle file uploads
        Route::middleware([\App\Http\Middleware\SecureFileUploads::class])->group(function() {
            Route::put('/profile',      [\App\Http\Controllers\User\UserController::class, 'updateProfile'])->name('user.profile.update');
        });
        
        Route::put('/preferences',      [\App\Http\Controllers\User\UserController::class, 'updatePreferences'])->name('user.preferences.update');
        Route::get('/change-password',  [\App\Http\Controllers\User\UserController::class, 'changePassword'])->name('user.change-password');
        Route::put('/change-password',  [\App\Http\Controllers\User\UserController::class, 'updatePassword'])->name('user.password.update');
    });
});

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::get('/dashboard',             [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    
    // Real-time data endpoints for dashboard
    Route::get('/api/realtime-data',     [\App\Http\Controllers\AdminController::class, 'getRealtimeData'])->name('api.realtime');
    Route::get('/api/dashboard-data',    [\App\Http\Controllers\AdminController::class, 'getDashboardData'])->name('api.dashboard');
    Route::get('/api/bookings',          [\App\Http\Controllers\AdminController::class, 'getFilteredBookings'])->name('api.bookings');
    Route::get('/api/fields',            [\App\Http\Controllers\AdminController::class, 'getFilteredFields'])->name('api.fields');
    Route::get('/api/users',             [\App\Http\Controllers\AdminController::class, 'getFilteredUsers'])->name('api.users');
    Route::get('/api/system-health',     [\App\Http\Controllers\AdminController::class, 'getSystemHealth'])->name('api.system-health');
    Route::get('/api/performance-metrics', [\App\Http\Controllers\AdminController::class, 'getPerformanceMetrics'])->name('api.performance');
    
    // Export functionality
    Route::get('/export/report',         [\App\Http\Controllers\AdminController::class, 'exportReport'])->name('export.report');
    Route::get('/export/dashboard-pdf',  [\App\Http\Controllers\AdminController::class, 'exportDashboardPDF'])->name('export.dashboard.pdf');
    Route::get('/export/dashboard-excel', [\App\Http\Controllers\AdminController::class, 'exportDashboardExcel'])->name('export.dashboard.excel');
    
    // System management
    Route::post('/system/settings',      [\App\Http\Controllers\AdminController::class, 'updateSystemSettings'])->name('system.settings');
    Route::get('/system/audit-log',      [\App\Http\Controllers\AdminController::class, 'getAuditLog'])->name('system.audit-log');
    Route::post('/system/clear-cache',   [\App\Http\Controllers\AdminController::class, 'clearSystemCache'])->name('system.clear-cache');
    Route::post('/system/backup',        [\App\Http\Controllers\AdminController::class, 'createBackup'])->name('system.backup');
    
    Route::get('/fields',                [\App\Http\Controllers\AdminController::class, 'fields'])->name('fields');
    Route::get('/fields/create',         [\App\Http\Controllers\AdminController::class, 'createField'])->name('fields.create');
    
    // Apply secure uploads middleware to routes that handle file uploads
    Route::middleware([\App\Http\Middleware\SecureFileUploads::class])->group(function() {
        Route::post('/fields',               [\App\Http\Controllers\AdminController::class, 'storeField'])->name('fields.store');
        Route::put('/fields/{field}',        [\App\Http\Controllers\AdminController::class, 'updateField'])->name('fields.update');
    });
    
    Route::get('/fields/{field}/edit',   [\App\Http\Controllers\AdminController::class, 'editField'])->name('fields.edit');
    Route::delete('/fields/{field}',     [\App\Http\Controllers\AdminController::class, 'destroyField'])->name('fields.destroy');
    Route::get('/bookings',              [\App\Http\Controllers\AdminController::class, 'bookings'])->name('bookings');
    Route::get('/users',                 [\App\Http\Controllers\AdminController::class, 'users'])->name('users'); // admin.users.index
    Route::put('/users/{user}/role',     [\App\Http\Controllers\AdminController::class, 'updateUserRole'])->name('users.role');
    Route::put('/users/{user}/toggle',   [\App\Http\Controllers\AdminController::class, 'toggleUserStatus'])->name('users.toggle');
});

// Owner routes
Route::prefix('owner')->name('owner.')->middleware(['auth', \App\Http\Middleware\FieldOwnerMiddleware::class])->group(function () {
    Route::get('/dashboard',                    [\App\Http\Controllers\Owner\OwnerController::class, 'dashboard'])->name('dashboard');
    Route::get('/fields',                       [\App\Http\Controllers\Owner\OwnerController::class, 'fields'])->name('fields');
    Route::get('/fields/create',                [\App\Http\Controllers\Owner\OwnerController::class, 'createField'])->name('fields.create');
    
    // Apply secure uploads middleware to routes that handle file uploads
    Route::middleware([\App\Http\Middleware\SecureFileUploads::class])->group(function() {
        Route::post('/fields',                      [\App\Http\Controllers\Owner\OwnerController::class, 'storeField'])->name('fields.store');
        Route::put('/fields/{field}',               [\App\Http\Controllers\Owner\OwnerController::class, 'updateField'])->name('fields.update');
    });
    
    Route::get('/fields/{field}/edit',          [\App\Http\Controllers\Owner\OwnerController::class, 'editField'])->name('fields.edit');
    Route::delete('/fields/{field}',            [\App\Http\Controllers\Owner\OwnerController::class, 'deleteField'])->name('fields.destroy');
    Route::get('/bookings',                     [\App\Http\Controllers\Owner\OwnerController::class, 'bookings'])->name('bookings');
    Route::put('/bookings/{booking}/status',    [\App\Http\Controllers\Owner\OwnerController::class, 'updateBookingStatus'])->name('bookings.status');
});

// API Routes moved to routes/api.php

// Routes for enhanced payment features
Route::middleware('auth')->group(function () {
    Route::post('/payment-notification-enhanced', [PaymentController::class, 'enhancedNotification'])->name('payment.notification.enhanced');
    Route::get('/payment-detail/{booking}', [PaymentController::class, 'getPaymentDetail'])->name('payment.detail');
});

// Test route for debugging review issues
Route::get('/debug-reviews', function() {
    if (!Auth::check()) {
        return 'User not authenticated';
    }
    
    $userId = Auth::id();
    $bookingsCount = App\Models\Booking::where('user_id', $userId)->count();
    $completedCount = App\Models\Booking::where('user_id', $userId)->where('status', 'completed')->count();
    $reviewableCount = App\Models\Booking::where('user_id', $userId)
        ->where('status', 'completed')
        ->whereDoesntHave('review')
        ->count();
    
    return "User ID: $userId<br>Total Bookings: $bookingsCount<br>Completed: $completedCount<br>Reviewable: $reviewableCount";
})->middleware('auth');

// Test route for payment status (temporary)
Route::get('/test-payment-status/{booking}', function ($bookingId) {
    $booking = App\Models\Booking::with('field')->find($bookingId);
    if (!$booking) {
        return response()->json(['error' => 'Booking not found']);
    }
    
    // Simulate the same logic as PaymentController->checkStatus
    $transactionStatus = $booking->payment_status === 'paid' ? 'settlement' : ($booking->payment_status ?? 'pending');
    
    return response()->json([
        'success' => true,
        'transaction_status' => $transactionStatus,
        'payment_type' => $booking->payment_method ?? 'test_payment',
        'gross_amount' => (float) $booking->total_price,
        'transaction_time' => $booking->paid_at ? $booking->paid_at->format('Y-m-d H:i:s') : $booking->updated_at->format('Y-m-d H:i:s'),
        'field_name' => $booking->field->name ?? 'Unknown Field',
        'booking_date' => $booking->date,
        'booking_time' => $booking->start_time . ' - ' . $booking->end_time,
        'order_id' => 'LK-' . $booking->id . '-' . $booking->created_at->timestamp,
        'transaction_id' => $booking->payment_id ?? 'TEST-' . $booking->id,
        'is_test_mode' => true,
        'message' => $transactionStatus === 'settlement' ? 'Pembayaran berhasil dan booking telah dikonfirmasi' : 'Pembayaran sedang menunggu konfirmasi'
    ]);
});

// Debug route for fixing blade templates
Route::get('/debug-fix', function() {
    return view('debugfix');
});

// Fallback
Route::fallback(function () {
    return view('pages.notfound');
});
