<?php
/**
 * LapangKuy Payment Access Diagnostic Tool
 * Menganalisis mengapa user tidak bisa akses halaman pembayaran
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Booking;
use App\Models\Field;
use Illuminate\Support\Facades\DB;

echo "=== LAPANGKUY PAYMENT ACCESS DIAGNOSTIC ===\n\n";

// 1. Check database structure and data
echo "1. CHECKING DATABASE STRUCTURE...\n";
try {
    $bookingStructure = DB::select("DESCRIBE bookings");
    echo "✓ Bookings table structure OK\n";
    
    $userStructure = DB::select("DESCRIBE users");
    echo "✓ Users table structure OK\n";
    
    $fieldStructure = DB::select("DESCRIBE fields");
    echo "✓ Fields table structure OK\n";
} catch (Exception $e) {
    echo "✗ Database structure error: " . $e->getMessage() . "\n";
}

// 2. Check booking data integrity
echo "\n2. CHECKING BOOKING DATA INTEGRITY...\n";
try {
    $totalBookings = Booking::count();
    echo "Total bookings: $totalBookings\n";
    
    $pendingBookings = Booking::where('status', 'pending')->count();
    echo "Pending bookings: $pendingBookings\n";
    
    $unpaidBookings = Booking::where('payment_status', 'unpaid')->count();
    echo "Unpaid bookings: $unpaidBookings\n";
    
    $pendingUnpaidBookings = Booking::where('status', 'pending')
                                   ->where('payment_status', 'unpaid')
                                   ->count();
    echo "Pending + Unpaid bookings: $pendingUnpaidBookings\n";
    
    // Check for orphaned bookings
    $orphanedBookings = DB::select("
        SELECT b.id, b.user_id, b.field_id, b.status, b.payment_status 
        FROM bookings b 
        LEFT JOIN users u ON b.user_id = u.id 
        LEFT JOIN fields f ON b.field_id = f.id 
        WHERE u.id IS NULL OR f.id IS NULL
        LIMIT 10
    ");
    
    if (count($orphanedBookings) > 0) {
        echo "⚠️  Found " . count($orphanedBookings) . " orphaned bookings\n";
        foreach ($orphanedBookings as $booking) {
            echo "  - Booking ID {$booking->id}: user_id={$booking->user_id}, field_id={$booking->field_id}\n";
        }
    } else {
        echo "✓ No orphaned bookings found\n";
    }
} catch (Exception $e) {
    echo "✗ Booking data check error: " . $e->getMessage() . "\n";
}

// 3. Check specific user scenarios
echo "\n3. TESTING USER ACCESS SCENARIOS...\n";
try {
    // Get a sample user with bookings
    $usersWithBookings = DB::select("
        SELECT u.id, u.name, u.email, COUNT(b.id) as booking_count
        FROM users u 
        LEFT JOIN bookings b ON u.id = b.user_id 
        WHERE u.role = 'user'
        GROUP BY u.id, u.name, u.email 
        HAVING booking_count > 0 
        ORDER BY booking_count DESC 
        LIMIT 5
    ");
    
    foreach ($usersWithBookings as $userData) {
        echo "User: {$userData->name} (ID: {$userData->id}) - {$userData->booking_count} bookings\n";
        
        $userBookings = Booking::where('user_id', $userData->id)
                              ->with(['field', 'user'])
                              ->get();
        
        foreach ($userBookings as $booking) {
            echo "  Booking #{$booking->id}: ";
            echo "Status={$booking->status}, ";
            echo "Payment={$booking->payment_status}, ";
            echo "Field=" . ($booking->field ? $booking->field->name : 'MISSING') . ", ";
            
            // Check if user can access payment
            $canPay = ($booking->user_id == $userData->id && 
                      $booking->status == 'pending' && 
                      $booking->payment_status == 'unpaid');
            
            echo "CanPay=" . ($canPay ? 'YES' : 'NO');
            
            if (!$canPay) {
                $reasons = [];
                if ($booking->user_id != $userData->id) $reasons[] = 'WrongUser';
                if ($booking->status != 'pending') $reasons[] = 'Status:' . $booking->status;
                if ($booking->payment_status != 'unpaid') $reasons[] = 'Payment:' . $booking->payment_status;
                echo " (" . implode(', ', $reasons) . ")";
            }
            echo "\n";
        }
    }
} catch (Exception $e) {
    echo "✗ User scenario test error: " . $e->getMessage() . "\n";
}

// 4. Check route accessibility
echo "\n4. CHECKING ROUTE DEFINITIONS...\n";
try {
    $routes = file_get_contents('routes/web.php');
    
    if (strpos($routes, 'bookings/{booking}/payment') !== false) {
        echo "✓ Payment routes defined\n";
    } else {
        echo "✗ Payment routes missing\n";
    }
    
    if (strpos($routes, "middleware(['auth'])") !== false) {
        echo "✓ Auth middleware applied\n";
    } else {
        echo "⚠️  Auth middleware not found in routes\n";
    }
} catch (Exception $e) {
    echo "✗ Route check error: " . $e->getMessage() . "\n";
}

// 5. Check payment token issues
echo "\n5. CHECKING PAYMENT TOKEN ISSUES...\n";
try {
    $bookingsWithNullTokens = Booking::whereNull('payment_token')
                                    ->where('payment_status', 'unpaid')
                                    ->count();
    echo "Bookings without payment tokens: $bookingsWithNullTokens\n";
    
    $bookingsWithEmptyTokens = Booking::where('payment_token', '')
                                     ->where('payment_status', 'unpaid')
                                     ->count();
    echo "Bookings with empty payment tokens: $bookingsWithEmptyTokens\n";
} catch (Exception $e) {
    echo "✗ Payment token check error: " . $e->getMessage() . "\n";
}

// 6. Generate recommendations
echo "\n6. RECOMMENDATIONS TO FIX PAYMENT ACCESS:\n";

echo "A. IMMEDIATE FIXES:\n";
echo "   1. Update BookingPolicy to allow payment for 'confirmed' status bookings\n";
echo "   2. Fix inconsistent booking statuses in database\n";
echo "   3. Regenerate missing payment tokens\n";

echo "\nB. PREVENTIVE MEASURES:\n";
echo "   1. Add database constraints for status consistency\n";
echo "   2. Implement status transition validations\n";
echo "   3. Add logging for payment access attempts\n";

echo "\n=== DIAGNOSTIC COMPLETE ===\n";
echo "Run this script to identify specific payment access issues.\n";
echo "Next: Apply the recommended fixes based on the findings above.\n";
