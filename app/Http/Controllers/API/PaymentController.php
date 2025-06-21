<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{    /**
     * Get all payments for the authenticated user
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            // Validate request parameters
            $validatedData = $request->validate([
                'payment_status' => 'sometimes|in:paid,pending,failed,expired',
                'page' => 'sometimes|integer|min:1',
                'per_page' => 'sometimes|integer|min:1|max:50',
            ]);
            
            // Get per page from request or use default
            $perPage = $request->input('per_page', 10);
            
            // Get user bookings with payment information
            $query = Booking::with(['field'])
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc');
            
            // Filter by payment status if provided
            if ($request->has('payment_status')) {
                $query->where('payment_status', $request->payment_status);
            }
            
            $payments = $query->paginate($perPage);
            
            // Check if we have any payments
            if ($payments->isEmpty() && $payments->currentPage() > 1) {
                // If we're on a page with no results, redirect to the first page
                return response()->json([
                    'status' => 'redirect',
                    'message' => 'No payments found on this page',
                    'redirect_to_page' => 1
                ]);
            }
            
            return response()->json([
                'status' => 'success',
                'data' => [
                    'payments' => $payments,
                    'filters' => [
                        'payment_status' => $request->input('payment_status', 'all')
                    ]
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Payment list validation error: ' . json_encode($e->errors()));
            return response()->json([
                'status' => 'error',
                'message' => 'Parameter tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Payment list error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memuat daftar pembayaran'
            ], 500);
        }
    }
      /**
     * Get all payment details for specific booking
     * 
     * @param int $id Booking ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            // Validate ID
            if (!is_numeric($id)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ID booking tidak valid'
                ], 400);
            }
            
            // Find booking with related data
            $booking = Booking::with(['field', 'user'])
                ->where('user_id', Auth::id())
                ->findOrFail($id);
            
            // Prepare payment details
            $paymentData = [
                'id' => $booking->id,
                'booking_code' => $booking->booking_code ?? 'LK' . str_pad($booking->id, 6, '0', STR_PAD_LEFT),
                'date' => $booking->date,
                'start_time' => $booking->start_time,
                'end_time' => $booking->end_time,
                'field_name' => $booking->field->name ?? 'Unknown field',
                'field_location' => $booking->field->location ?? 'Unknown location',
                'total_price' => $booking->total_price,
                'payment_status' => $booking->payment_status,
                'payment_method' => $booking->payment_method ?? 'N/A',
                'transaction_id' => $booking->transaction_id ?? 'N/A',
                'payment_date' => $booking->paid_at ? $booking->paid_at->format('Y-m-d H:i:s') : null,
                'created_at' => $booking->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $booking->updated_at->format('Y-m-d H:i:s'),
                'can_retry_payment' => in_array($booking->payment_status, ['pending', 'failed', 'expired']) && 
                                      $booking->status !== 'cancelled'
            ];
            
            return response()->json([
                'status' => 'success',
                'data' => [
                    'payment' => $paymentData
                ]
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Payment not found: ' . $id);
            return response()->json([
                'status' => 'error',
                'message' => 'Pembayaran tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Payment detail error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memuat detail pembayaran'
            ], 500);
        }
    }
    
    /**
     * Get payment methods available in the system
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPaymentMethods()
    {
        try {
            // Get available payment methods from configuration
            $paymentMethods = [
                [
                    'type' => 'credit_card',
                    'name' => 'Kartu Kredit/Debit',
                    'description' => 'Visa, Mastercard, JCB',
                    'icon' => 'credit-card',
                    'color' => 'primary',
                    'available' => true
                ],
                [
                    'type' => 'bank_transfer',
                    'name' => 'Transfer Bank',
                    'description' => 'BCA, Mandiri, BNI, BRI',
                    'icon' => 'university',
                    'color' => 'success',
                    'available' => true
                ],
                [
                    'type' => 'ewallet',
                    'name' => 'E-Wallet',
                    'description' => 'GoPay, OVO, DANA, LinkAja',
                    'icon' => 'mobile-alt',
                    'color' => 'warning',
                    'available' => true
                ]
            ];
            
            return response()->json([
                'status' => 'success',
                'data' => [
                    'payment_methods' => $paymentMethods
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Payment methods error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memuat metode pembayaran'
            ], 500);
        }
    }
}
