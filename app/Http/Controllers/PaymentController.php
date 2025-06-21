<?php

namespace App\Http\Controllers;

use App\Mail\PaymentSuccess;
use App\Models\Booking;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class PaymentController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }    /**
     * Display a listing of all payment records.
     */
    public function listPayments()
    {
        // Get all bookings with payments for the authenticated user
        $payments = Booking::where('user_id', auth()->id())
                    ->whereNotNull('payment_token')
                    ->with('field')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return view('bookings.payments', compact('payments'));
    }    /**
     * Show payment page for a booking.
     */
    public function show(Booking $booking)
    {
        // Use the BookingPolicy to check payment access
        $this->authorize('pay', $booking);

        // Additional checks for payment status
        if ($booking->payment_status === 'paid') {
            return redirect()->route('bookings.show', $booking)->with('info', 'Booking ini sudah dibayar.');
        }
        
        // Block access for cancelled or completed bookings
        if (in_array($booking->status, ['cancelled', 'completed'])) {
            return redirect()->route('bookings.show', $booking)->with('error', 'Booking ini tidak dapat dibayar karena sudah ' . $booking->status . '.');
        }// Create or get payment token
        try {
            if (!$booking->payment_token) {            // Check for testing mode first
                if (empty(config('midtrans.server_key')) || 
                    config('midtrans.server_key') === 'SB-Mid-server-xxxxxxxxxxxxxxxx' ||
                    !config('midtrans.server_key') ||
                    config('midtrans.server_key') === null) {
                    
                    // Testing mode - create fake token
                    $fakeToken = 'fake-' . md5($booking->id . time());
                    $booking->update(['payment_token' => $fakeToken]);
                    $snapToken = $fakeToken;
                    
                    Log::info('Using test mode payment token', ['booking_id' => $booking->id]);
                } else {
                    // Log Midtrans environment before making API call
                    Log::info('Attempting real Midtrans payment', [
                        'booking_id' => $booking->id,
                        'environment' => config('midtrans.is_production') ? 'PRODUCTION' : 'SANDBOX',
                        'server_key_prefix' => substr(config('midtrans.server_key'), 0, 6) . '...'
                    ]);
                    
                    // Real Midtrans integration - get proper token
                    $snapToken = $this->midtransService->createTransaction($booking);
                }
            } else {
                $snapToken = $booking->payment_token;
            }

            return view('bookings.payment', compact('booking', 'snapToken'));
        } catch (\Exception $e) {
            Log::error('Payment creation failed: ' . $e->getMessage(), [
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'exception' => $e->getTraceAsString()
            ]);
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'Gagal membuat pembayaran. Silakan coba lagi.');
        }
    }    /**
     * Process the payment.
     */
    public function process(Request $request, Booking $booking)
    {
        // Use the BookingPolicy to check payment access
        $this->authorize('pay', $booking);

        try {
            // Check if booking exists in database
            if (!$booking || !$booking->id) {
                throw new \Exception('Booking record not found');
            }
            
            // Check if it's already paid
            if ($booking->payment_status === 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking ini sudah dibayar',
                    'redirect' => route('bookings.show', $booking)
                ]);
            }
            
            // Cek apakah dalam mode testing
            if (empty(config('midtrans.server_key')) || config('midtrans.server_key') === 'SB-Mid-server-xxxxxxxxxxxxxxxx') {
                // Mode testing, gunakan token palsu
                $fakeToken = 'fake-' . md5($booking->id . time());
                $booking->update(['payment_token' => $fakeToken]);
                
                return response()->json([
                    'success' => true,
                    'snap_token' => $fakeToken,
                    'client_key' => config('midtrans.client_key'),
                    'is_test_mode' => true
                ]);
            } else {
                // Mode produksi/sandbox normal
                $snapToken = $this->midtransService->createTransaction($booking);
                
                return response()->json([
                    'success' => true,
                    'snap_token' => $snapToken,
                    'client_key' => config('midtrans.client_key')
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Payment processing failed: ' . $e->getMessage(), [
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'exception' => $e
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pembayaran. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Handle Midtrans notification callback
     */
    public function notification(Request $request)
    {
        try {
            $booking = $this->midtransService->handleNotification();
            
            Log::info('Payment notification processed', [
                'booking_id' => $booking->id,
                'payment_status' => $booking->payment_status,
                'booking_status' => $booking->status
            ]);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Payment notification failed: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }    }    /**
     * Handle the case when a booking is not found
     */
    public function showNotFound()
    {
        return view('errors.booking-not-found');
    }

    /**
     * Redirect old success/failed routes to status page
     */
    public function redirectToStatus(Booking $booking)
    {
        return redirect()->route('bookings.payment.status', $booking);
    }/**
     * Check payment status via API (consolidated method)
     */
    public function checkStatus(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false, 
                'message' => 'User not logged in'
            ], 401);
        }

        // Handle both booking_id and order_id parameters
        $bookingId = $request->input('booking_id');
        $orderId = $request->input('order_id');
        
        if (!$bookingId && !$orderId) {
            return response()->json([
                'success' => false, 
                'message' => 'No booking ID or order ID provided'
            ], 400);
        }

        try {
            // If order_id is provided, extract booking_id from it
            if ($orderId && !$bookingId) {
                $parts = explode('-', $orderId);
                $bookingId = $parts[1] ?? null;
            }

            if (!$bookingId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid booking or order ID format',
                    'transaction_status' => 'error'
                ]);
            }

            // Find booking with payment details
            $booking = Booking::with(['field', 'user'])
                ->where('id', $bookingId)
                ->where('user_id', Auth::id())
                ->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found',
                    'transaction_status' => 'error'
                ]);
            }            // In test mode, return current booking status with SiTumbuh-style formatting
            if (empty(config('midtrans.server_key')) || config('midtrans.server_key') === 'SB-Mid-server-xxxxxxxxxxxxxxxx') {
                // Direct status mapping like SiTumbuh - no API calls needed
                $transactionStatus = $this->mapPaymentStatusToTransaction($booking->payment_status);
                
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
                    'message' => $this->getStatusMessage($transactionStatus)
                ]);
            }

            // Real Midtrans status check
            $orderId = 'LK-' . $booking->id . '-' . $booking->created_at->timestamp;
            $status = $this->midtransService->checkTransactionStatus($orderId);

            // Update booking based on Midtrans response
            $updateData = [];

            if ($status->transaction_status === 'settlement' || $status->transaction_status === 'capture') {
                $updateData['payment_status'] = 'paid';
                $updateData['status'] = 'confirmed';
                $updateData['paid_at'] = now();
            } elseif ($status->transaction_status === 'pending') {
                $updateData['payment_status'] = 'pending';
            } elseif (in_array($status->transaction_status, ['deny', 'expire', 'cancel'])) {
                $updateData['payment_status'] = 'failed';
                $updateData['status'] = 'cancelled';
            }

            if (!empty($updateData)) {
                $booking->update($updateData);
                $booking->refresh();
            }

            return response()->json([
                'success' => true,
                'order_id' => $orderId,
                'transaction_status' => $status->transaction_status ?? $booking->payment_status,
                'payment_type' => $status->payment_type ?? $booking->payment_method ?? 'Unknown',
                'gross_amount' => $booking->total_price,
                'transaction_time' => $booking->paid_at ? $booking->paid_at->toISOString() : now()->toISOString(),
                'field_name' => $booking->field->name ?? 'Unknown Field',
                'booking_date' => $booking->date,
                'booking_time' => $booking->start_time . ' - ' . $booking->end_time,
                'transaction_id' => $status->transaction_id ?? null
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking payment status: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error checking payment status',
                'transaction_status' => 'error',
                'payment_type' => 'Unknown',
                'gross_amount' => 0,
                'transaction_time' => now()->toISOString()
            ]);
        }
    }

    /**
     * Enhanced notification handler for Midtrans (inspired by stunting project)
     */
    public function enhancedNotification(Request $request)
    {
        try {
            $notificationData = $request->all();
            
            Log::info('Received payment notification: ', $notificationData);
            
            // Extract transaction details
            $orderId = $notificationData['order_id'] ?? null;
            $transactionStatus = $notificationData['transaction_status'] ?? null;
            $paymentType = $notificationData['payment_type'] ?? null;
            $transactionTime = $notificationData['transaction_time'] ?? null;
            $fraudStatus = $notificationData['fraud_status'] ?? null;

            if (!$orderId || !$transactionStatus) {
                Log::error('Invalid notification data received');
                return response('Invalid notification data', 400);
            }

            // Extract booking ID from order_id
            $parts = explode('-', $orderId);
            $bookingId = $parts[1] ?? null;

            if (!$bookingId) {
                Log::warning("Invalid order ID format: {$orderId}");
                return response('OK, invalid order format', 200);
            }

            // Find booking record
            $booking = Booking::find($bookingId);

            if (!$booking) {
                Log::warning("Booking not found for order ID: {$orderId}");
                return response('OK, booking not found', 200);
            }

            // Update booking details based on transaction status
            $updateData = [
                'payment_method' => $paymentType,
            ];

            // Parse transaction time
            if ($transactionTime) {
                try {
                    $updateData['paid_at'] = Carbon::parse($transactionTime);
                } catch (\Exception $e) {
                    Log::warning("Could not parse transaction time: {$transactionTime}");
                    $updateData['paid_at'] = now();
                }
            }

            // Handle different transaction statuses
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $updateData['payment_status'] = 'challenge';
                    $updateData['status'] = 'pending';
                } else if ($fraudStatus == 'accept') {
                    $updateData['payment_status'] = 'paid';
                    $updateData['status'] = 'confirmed';
                    $updateData['paid_at'] = $updateData['paid_at'] ?? now();
                }
            } else if ($transactionStatus == 'settlement') {
                $updateData['payment_status'] = 'paid';
                $updateData['status'] = 'confirmed';
                $updateData['paid_at'] = $updateData['paid_at'] ?? now();
            } else if ($transactionStatus == 'pending') {
                $updateData['payment_status'] = 'pending';
                $updateData['status'] = 'pending';
            } else if ($transactionStatus == 'deny') {
                $updateData['payment_status'] = 'failed';
                $updateData['status'] = 'cancelled';
            } else if ($transactionStatus == 'expire') {
                $updateData['payment_status'] = 'expired';
                $updateData['status'] = 'cancelled';
            } else if ($transactionStatus == 'cancel') {
                $updateData['payment_status'] = 'cancelled';
                $updateData['status'] = 'cancelled';
            }

            // Update booking
            $booking->update($updateData);

            Log::info("Payment updated successfully: {$orderId} -> {$transactionStatus}");
            
            // Send email notification if payment successful
            if (in_array($updateData['payment_status'], ['paid'])) {
                // You can add email notification here
                // Mail::to($booking->user)->send(new PaymentSuccessNotification($booking));
            }

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('Error processing notification: ' . $e->getMessage());
            // Always return 200 to prevent Midtrans from retrying
            return response('OK, error logged', 200);
        }
    }

    /**
     * Get payment detail for modal display
     */
    public function getPaymentDetail($bookingId)
    {
        try {
            $booking = Booking::with(['field', 'user'])
                ->where('id', $bookingId)
                ->where('user_id', Auth::id())
                ->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            // Format dates for display
            $formattedDate = $booking->date ? Carbon::parse($booking->date)->format('d F Y') : null;
            $formattedPaidAt = $booking->paid_at ? $booking->paid_at->format('d F Y H:i') : null;

            return response()->json([
                'success' => true,
                'payment' => [
                    'id' => $booking->id,
                    'order_id' => 'LK-' . $booking->id . '-' . $booking->created_at->timestamp,
                    'field_name' => $booking->field->name ?? 'Unknown Field',
                    'date' => $formattedDate,
                    'start_time' => $booking->start_time,
                    'end_time' => $booking->end_time,
                    'total_price' => $booking->total_price,
                    'payment_status' => $booking->payment_status,
                    'payment_method' => $booking->payment_method,
                    'payment_date' => $formattedPaidAt,
                    'transaction_id' => $booking->payment_id,
                    'status' => $booking->status
                ]
            ]);        } catch (\Exception $e) {
            Log::error('Error fetching payment detail: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error fetching payment details'
            ], 500);
        }
    }    /**
     * Show payment status page for a booking
     */
    public function status(Booking $booking, Request $request)
    {
        // Hapus pengecekan akses user ke booking
        // if ($booking->user_id !== Auth::id()) {
        //     return redirect()->route('bookings.index')->with('error', 'Anda tidak memiliki akses ke booking ini.');
        // }

        // SiTumbuh style: Auto-update status based on URL parameters
        $transactionStatus = $request->get('transaction_status');
        $orderId = $request->get('order_id');
        
        if ($transactionStatus && in_array($transactionStatus, ['settlement', 'capture', 'success', 'pending', 'deny', 'cancel', 'expire', 'failed'])) {
            $this->updateBookingFromUrlParams($booking, $transactionStatus, $orderId);
        }

        return view('bookings.payment-status', compact('booking'));
    }

    /**
     * Update booking status based on URL parameters (SiTumbuh style)
     */
    private function updateBookingFromUrlParams(Booking $booking, $transactionStatus, $orderId = null)
    {
        $updateData = [];

        // Map transaction status to booking status
        switch ($transactionStatus) {
            case 'settlement':
            case 'capture':
            case 'success':
                $updateData['payment_status'] = 'paid';
                $updateData['status'] = 'confirmed';
                $updateData['paid_at'] = now();
                $updateData['payment_method'] = 'settlement';
                break;
                
            case 'pending':
                $updateData['payment_status'] = 'pending';
                $updateData['status'] = 'pending';
                break;
                
            case 'deny':
            case 'cancel':
            case 'expire':
            case 'failed':
                $updateData['payment_status'] = 'failed';
                $updateData['status'] = 'cancelled';
                break;
        }

        // Add order ID if provided
        if ($orderId) {
            $updateData['payment_id'] = $orderId;
        }

        if (!empty($updateData)) {
            $booking->update($updateData);
            
            Log::info("Payment status updated from URL params", [
                'booking_id' => $booking->id,
                'transaction_status' => $transactionStatus,
                'order_id' => $orderId,
                'updated_fields' => $updateData
            ]);
        }
    }

    /**
     * Update payment status manually (for testing or manual verification)
     * Similar to SiTumbuh project approach
     */
    public function updatePaymentStatus(Request $request, Booking $booking)
    {
        if (!Auth::check() || $booking->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $request->validate([
            'transaction_status' => 'required|string',
            'payment_type' => 'nullable|string',
            'transaction_id' => 'nullable|string'
        ]);

        try {
            $transactionStatus = $request->input('transaction_status');
            $paymentType = $request->input('payment_type', 'manual_update');
            $transactionId = $request->input('transaction_id');

            $updateData = [
                'payment_method' => $paymentType,
            ];

            // Handle different transaction statuses like SiTumbuh
            switch ($transactionStatus) {
                case 'settlement':
                case 'capture':
                case 'success':
                    $updateData['payment_status'] = 'paid';
                    $updateData['status'] = 'confirmed';
                    $updateData['paid_at'] = now();
                    break;
                    
                case 'pending':
                    $updateData['payment_status'] = 'pending';
                    $updateData['status'] = 'pending';
                    break;
                    
                case 'deny':
                case 'cancel':
                case 'expire':
                case 'failed':
                    $updateData['payment_status'] = 'failed';
                    $updateData['status'] = 'cancelled';
                    break;
                    
                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid transaction status'
                    ], 400);
            }

            // Add transaction ID if provided
            if ($transactionId) {
                $updateData['payment_id'] = $transactionId;
            }

            $booking->update($updateData);

            Log::info("Manual payment status update", [
                'booking_id' => $booking->id,
                'old_status' => $booking->getOriginal('payment_status'),
                'new_status' => $updateData['payment_status'],
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment status updated successfully',
                'transaction_status' => $updateData['payment_status'],
                'payment_type' => $paymentType,
                'gross_amount' => (float) $booking->total_price
            ]);

        } catch (\Exception $e) {
            Log::error('Manual payment status update failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update payment status'
            ], 500);
        }
    }    /**
     * Map internal payment status to Midtrans transaction status (SiTumbuh style)
     */
    private function mapPaymentStatusToTransaction($paymentStatus)
    {
        $statusMap = [
            'paid' => 'settlement',
            'pending' => 'pending', 
            'failed' => 'deny',
            'cancelled' => 'cancel',
            'expired' => 'expire'
        ];

        return $statusMap[$paymentStatus] ?? 'pending';
    }

    /**
     * Get user-friendly status message (SiTumbuh style)
     */
    private function getStatusMessage($transactionStatus)
    {
        $messages = [
            'settlement' => 'Pembayaran berhasil dan booking telah dikonfirmasi',
            'pending' => 'Pembayaran sedang menunggu konfirmasi',
            'deny' => 'Pembayaran ditolak oleh bank',
            'cancel' => 'Pembayaran dibatalkan oleh pengguna',
            'expire' => 'Pembayaran telah kedaluwarsa',
            'failed' => 'Pembayaran gagal diproses'
        ];

        return $messages[$transactionStatus] ?? 'Status pembayaran tidak diketahui';
    }
}
