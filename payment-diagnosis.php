<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';

// Get the kernel and bootstrap the application
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== PAYMENT ACCESS DIAGNOSIS ===\n\n";

try {
    // 1. Check unpaid bookings
    echo "1. UNPAID BOOKINGS ANALYSIS:\n";
    $unpaidBookings = Booking::where('payment_status', 'unpaid')->get();
    echo "Total unpaid bookings: " . $unpaidBookings->count() . "\n\n";
    
    foreach($unpaidBookings as $booking) {
        echo "Booking #{$booking->id}:\n";
        echo "  - Status: {$booking->status}\n";
        echo "  - Payment Status: {$booking->payment_status}\n";
        echo "  - User ID: {$booking->user_id}\n";
        echo "  - Field ID: {$booking->field_id}\n";
        echo "  - Created: {$booking->created_at}\n";
        
        // Check if booking can be paid according to policy
        $canPay = ($booking->status === 'pending' && $booking->payment_status === 'unpaid');
        echo "  - Can Pay (Policy): " . ($canPay ? 'YES' : 'NO') . "\n";
        
        if (!$canPay) {
            $issues = [];
            if ($booking->status !== 'pending') {
                $issues[] = "Status is '{$booking->status}' (should be 'pending')";
            }
            if ($booking->payment_status !== 'unpaid') {
                $issues[] = "Payment status is '{$booking->payment_status}' (should be 'unpaid')";
            }
            echo "  - Issues: " . implode(', ', $issues) . "\n";
        }
        echo "\n";
    }
    
    // 2. Check all booking statuses
    echo "2. BOOKING STATUS DISTRIBUTION:\n";
    $statusCounts = DB::table('bookings')
        ->select('status', DB::raw('count(*) as count'))
        ->groupBy('status')
        ->get();
    
    foreach($statusCounts as $status) {
        echo "  - {$status->status}: {$status->count} bookings\n";
    }
    echo "\n";
    
    // 3. Check payment status distribution
    echo "3. PAYMENT STATUS DISTRIBUTION:\n";
    $paymentCounts = DB::table('bookings')
        ->select('payment_status', DB::raw('count(*) as count'))
        ->groupBy('payment_status')
        ->get();
    
    foreach($paymentCounts as $payment) {
        echo "  - {$payment->payment_status}: {$payment->count} bookings\n";
    }
    echo "\n";
    
    // 4. Check problematic combinations
    echo "4. PROBLEMATIC BOOKING COMBINATIONS:\n";
    
    // Paid bookings that are still pending
    $paidPending = Booking::where('payment_status', 'paid')
        ->where('status', 'pending')
        ->count();
    echo "  - Paid but still pending: {$paidPending} bookings\n";
    
    // Confirmed bookings that are unpaid
    $confirmedUnpaid = Booking::where('status', 'confirmed')
        ->where('payment_status', 'unpaid')
        ->count();
    echo "  - Confirmed but unpaid: {$confirmedUnpaid} bookings\n";
    
    // Cancelled bookings that are paid
    $cancelledPaid = Booking::where('status', 'cancelled')
        ->where('payment_status', 'paid')
        ->count();
    echo "  - Cancelled but paid: {$cancelledPaid} bookings\n";
    echo "\n";
    
    // 5. Check users with access issues
    echo "5. USERS WITH POTENTIAL ACCESS ISSUES:\n";
    $users = User::where('role', 'user')->take(5)->get();
    
    foreach($users as $user) {
        $userBookings = Booking::where('user_id', $user->id)->get();
        $canPayCount = 0;
        $cantPayCount = 0;
        
        foreach($userBookings as $booking) {
            $canPay = ($booking->status === 'pending' && $booking->payment_status === 'unpaid');
            if ($canPay) {
                $canPayCount++;
            } else {
                $cantPayCount++;
            }
        }
        
        if ($userBookings->count() > 0) {
            echo "  - User {$user->name} (ID: {$user->id}):\n";
            echo "    Total bookings: {$userBookings->count()}\n";
            echo "    Can pay: {$canPayCount}\n";
            echo "    Cannot pay: {$cantPayCount}\n";
            
            if ($cantPayCount > 0) {
                echo "    Reasons for cannot pay:\n";
                foreach($userBookings as $booking) {
                    $canPay = ($booking->status === 'pending' && $booking->payment_status === 'unpaid');
                    if (!$canPay) {
                        echo "      Booking #{$booking->id}: status='{$booking->status}', payment='{$booking->payment_status}'\n";
                    }
                }
            }
            echo "\n";
        }
    }
    
    // 6. Recommendations
    echo "6. RECOMMENDED FIXES:\n";
    echo "Based on the analysis above, here are the recommended fixes:\n\n";
    
    if ($confirmedUnpaid > 0) {
        echo "A. URGENT: Fix {$confirmedUnpaid} confirmed but unpaid bookings\n";
        echo "   - These bookings are confirmed but payment status is still 'unpaid'\n";
        echo "   - Users cannot access payment page for these\n";
        echo "   - Run: UPDATE bookings SET payment_status='paid' WHERE status='confirmed' AND payment_status='unpaid'\n\n";
    }
    
    if ($paidPending > 0) {
        echo "B. URGENT: Fix {$paidPending} paid but pending bookings\n";
        echo "   - These bookings are paid but status is still 'pending'\n";
        echo "   - Run: UPDATE bookings SET status='confirmed' WHERE payment_status='paid' AND status='pending'\n\n";
    }
    
    echo "C. POLICY IMPROVEMENT:\n";
    echo "   - Current policy only allows payment for 'pending' + 'unpaid' bookings\n";
    echo "   - Consider allowing payment for 'confirmed' + 'unpaid' bookings too\n";
    echo "   - Add fallback for payment re-attempts\n\n";
    
    echo "D. PREVENTIVE MEASURES:\n";
    echo "   - Add database triggers to maintain status consistency\n";
    echo "   - Implement booking status transition validation\n";
    echo "   - Add logging for payment access attempts\n\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "=== DIAGNOSIS COMPLETE ===\n";
