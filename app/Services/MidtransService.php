<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\CoreApi;
use Midtrans\Notification;
use App\Models\Booking;
use Carbon\Carbon;

class MidtransService
{    
    public function __construct()
    {
        // Set your Merchant Server Key
        Config::$serverKey = config('midtrans.server_key');
        
        // Set production mode based on configuration
        Config::$isProduction = config('midtrans.is_production', false);
        
        // Enable sanitization and 3DS by default
        Config::$isSanitized = true;
        Config::$is3ds = true;
        
        // Log configuration for debugging
        $serverKey = config('midtrans.server_key');
        $clientKey = config('midtrans.client_key');
        
        \Log::info('Midtrans Configuration', [
            'server_key_exists' => !empty($serverKey),
            'client_key_exists' => !empty($clientKey),
            'server_key_prefix' => substr($serverKey, 0, 6) . '...',
            'client_key_prefix' => substr($clientKey, 0, 6) . '...',
            'environment' => Config::$isProduction ? 'PRODUCTION' : 'SANDBOX',
            'midtrans_url' => Config::$isProduction ? 'https://app.midtrans.com' : 'https://app.sandbox.midtrans.com'
        ]);
        
        // Additional verification
        if (empty($serverKey) || empty($clientKey)) {
            \Log::error('Midtrans keys are not properly configured!');
        }
    }

    /**
     * Create payment transaction
     */    public function createTransaction(Booking $booking)
    {
        // Load relationships jika belum dimuat
        if (!$booking->relationLoaded('user')) {
            $booking->load('user');
        }
        if (!$booking->relationLoaded('field')) {
            $booking->load('field');
        }

        // Pastikan ada user dan field
        if (!$booking->user) {
            throw new \Exception('User tidak ditemukan untuk booking ini');
        }
        if (!$booking->field) {
            throw new \Exception('Field tidak ditemukan untuk booking ini');
        }        // Cek apakah konfigurasi Midtrans valid
        if (empty(config('midtrans.server_key')) || config('midtrans.server_key') === 'SB-Mid-server-xxxxxxxxxxxxxxxx') {
            // Gunakan mode testing, buat token palsu
            $fakeToken = 'fake-' . md5($booking->id . time());
            
            // Update booking dengan token palsu
            $booking->update(['payment_token' => $fakeToken]);
            
            return $fakeToken;
        }
        
        // Mode produksi atau sandbox dengan key yang valid
        $orderId = 'LK-' . $booking->id . '-' . date('YmdHis');
        
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $booking->total_price,
            ],
            'customer_details' => [
                'first_name' => $booking->user->name ?? 'Customer',
                'email' => $booking->user->email ?? 'customer@example.com',
                'phone' => $booking->user->phone ?? '',
            ],
            'item_details' => [
                [
                    'id' => 'FIELD-' . $booking->field->id,
                    'price' => (int) $booking->total_price,
                    'quantity' => 1,
                    'name' => 'Booking Lapangan ' . ($booking->field->name ?? 'Unknown'),
                    'category' => 'Sports Field Booking',
                ]
            ],'callbacks' => [
                'finish' => route('bookings.payment.success', $booking->id),
                'error' => route('bookings.payment.failed', $booking->id),
                'pending' => route('bookings.payment.success', $booking->id),
            ],
            'expiry' => [
                'start_time' => date('Y-m-d H:i:s O'),
                'unit' => 'hours',
                'duration' => 24,
            ],            'custom_field1' => 'LapangKuy Booking',
            'custom_field2' => 'Field: ' . ($booking->field->name ?? 'Unknown'),
            'custom_field3' => 'Date: ' . (isset($booking->date) && $booking->date ? \Carbon\Carbon::parse($booking->date)->format('d/m/Y') : date('d/m/Y')),
        ];        try {
            // Log request details for debugging purposes
            \Log::debug('Midtrans payment request', [
                'order_id' => $orderId,
                'amount' => (int) $booking->total_price,
                'customer' => $booking->user->name ?? 'Unknown',
                'field' => $booking->field->name ?? 'Unknown',
            ]);
            
            $snapToken = Snap::getSnapToken($params);
            
            // Update booking with payment token
            $booking->update([
                'payment_token' => $snapToken,
                'payment_status' => 'pending'
            ]);
            
            // Log successful token generation
            \Log::info('Midtrans token generated successfully', [
                'booking_id' => $booking->id,
                'token_length' => strlen($snapToken)
            ]);

            return $snapToken;
        } catch (\Exception $e) {
            // Log detailed exception for debugging
            \Log::error('Failed to create Midtrans payment', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'params' => json_encode($params)
            ]);
            
            throw new \Exception('Failed to create payment: ' . $e->getMessage());
        }
    }

    /**
     * Handle payment notification
     */
    public function handleNotification()
    {
        try {
            $notification = new Notification();
            
            $transactionStatus = $notification->transaction_status;
            $orderId = $notification->order_id;
            $fraudStatus = $notification->fraud_status;

            // Extract booking ID from order_id (format: LK-{booking_id}-{timestamp})
            $parts = explode('-', $orderId);
            $bookingId = $parts[1] ?? null;

            if (!$bookingId) {
                throw new \Exception('Invalid order ID format');
            }

            $booking = Booking::find($bookingId);
            if (!$booking) {
                throw new \Exception('Booking not found');
            }

            // Update payment information
            $booking->update([
                'payment_id' => $notification->transaction_id,
                'payment_method' => $notification->payment_type ?? 'unknown',
            ]);

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $booking->update(['payment_status' => 'challenge']);
                } else if ($fraudStatus == 'accept') {
                    $this->updateBookingStatus($booking, 'paid', 'confirmed');
                }
            } else if ($transactionStatus == 'settlement') {
                $this->updateBookingStatus($booking, 'paid', 'confirmed');
            } else if ($transactionStatus == 'pending') {
                $booking->update(['payment_status' => 'pending']);
            } else if ($transactionStatus == 'deny') {
                $this->updateBookingStatus($booking, 'failed', 'cancelled');
            } else if ($transactionStatus == 'expire') {
                $this->updateBookingStatus($booking, 'expired', 'cancelled');
            } else if ($transactionStatus == 'cancel') {
                $this->updateBookingStatus($booking, 'cancelled', 'cancelled');
            }

            return $booking;
        } catch (\Exception $e) {
            throw new \Exception('Failed to handle notification: ' . $e->getMessage());
        }
    }

    /**
     * Update booking status
     */
    private function updateBookingStatus(Booking $booking, $paymentStatus, $bookingStatus)
    {
        $booking->update([
            'payment_status' => $paymentStatus,
            'status' => $bookingStatus,
            'paid_at' => $paymentStatus === 'paid' ? now() : null,
        ]);

        // Send confirmation email if payment successful
        if ($paymentStatus === 'paid') {
            // You can add email notification here
            // Mail::to($booking->user)->send(new PaymentSuccessNotification($booking));
        }
    }

    /**
     * Check transaction status
     */
    public function checkTransactionStatus($orderId)
    {
        try {
            $status = CoreApi::status($orderId);
            return $status;
        } catch (\Exception $e) {
            throw new \Exception('Failed to check transaction status: ' . $e->getMessage());
        }
    }

    /**
     * Cancel transaction
     */
    public function cancelTransaction($orderId)
    {
        try {
            $cancel = CoreApi::cancel($orderId);
            return $cancel;
        } catch (\Exception $e) {
            throw new \Exception('Failed to cancel transaction: ' . $e->getMessage());
        }
    }
    
    /**
     * Test API connection to Midtrans
     * 
     * @return array Connection status information
     */
    public function testConnection()
    {
        $result = [
            'success' => false,
            'server_key' => null,
            'server_key_valid' => false,
            'environment' => null,
            'message' => '',
            'error' => null
        ];
        
        try {
            // Check server key format
            $serverKey = config('midtrans.server_key');
            $result['server_key'] = substr($serverKey, 0, 6) . '...' . substr($serverKey, -4);
            $result['environment'] = (strpos($serverKey, 'SB-') === 0) ? 'SANDBOX' : 'PRODUCTION';
            
            // Log current configuration
            $info = [
                'server_key_prefix' => substr($serverKey, 0, 6),
                'is_production' => Config::$isProduction,
                'environment_setting' => $result['environment'],
                'environment_mismatch' => ($result['environment'] === 'SANDBOX' && Config::$isProduction) || 
                                          ($result['environment'] === 'PRODUCTION' && !Config::$isProduction)
            ];
            \Log::info('Midtrans connection test configuration', $info);
            
            // Make a simple API call to test credentials
            $testOrderId = 'TEST-' . time();
            $params = [
                'transaction_details' => [
                    'order_id' => $testOrderId,
                    'gross_amount' => 10000,
                ],
            ];
            
            // Just get a token without storing it
            Snap::getSnapToken($params);
            
            // If we got here, API call was successful
            $result['success'] = true;
            $result['server_key_valid'] = true;
            $result['message'] = 'Connection successful to Midtrans ' . $result['environment'] . ' API';
            
            return $result;
            
        } catch (\Exception $e) {
            $result['error'] = $e->getMessage();
            $result['message'] = 'Connection failed: ' . $e->getMessage();
            
            // Check if it's an authentication error
            if (strpos($e->getMessage(), '401') !== false || 
                strpos($e->getMessage(), 'Access denied') !== false || 
                strpos($e->getMessage(), 'unauthorized') !== false) {
                
                $result['server_key_valid'] = false;
                $envMismatch = '';
                
                // Check for environment mismatch
                if (($result['environment'] === 'SANDBOX' && Config::$isProduction) || 
                    ($result['environment'] === 'PRODUCTION' && !Config::$isProduction)) {
                    $envMismatch = ' Environment mismatch detected!';
                }
                
                $result['message'] = 'Authentication failed. Invalid server key or' . $envMismatch;
            }
            
            \Log::error('Midtrans connection test failed', [
                'error' => $e->getMessage(),
                'environment' => $result['environment'],
                'is_production_setting' => Config::$isProduction
            ]);
            
            return $result;
        }
    }
}
