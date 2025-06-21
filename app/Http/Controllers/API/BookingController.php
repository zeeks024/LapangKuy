<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Get all bookings for the authenticated user
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function userBookings(Request $request)
    {
        try {
            // Validate request parameters
            $validatedData = $request->validate([
                'status' => 'sometimes|in:pending,confirmed,completed,cancelled',
                'page' => 'sometimes|integer|min:1',
                'per_page' => 'sometimes|integer|min:1|max:50',
            ]);
            
            // Get per page from request or use default
            $perPage = $request->input('per_page', 10);
            
            // Get user bookings with field information
            $query = Booking::where('user_id', Auth::id())
                ->with('field')  // Eager load the field relationship
                ->orderBy('date', 'desc')
                ->orderBy('start_time', 'desc');
            
            // Filter by booking status if provided
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
            
            $bookings = $query->paginate($perPage);
            
            return response()->json([
                'status' => 'success',
                'data' => $bookings
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Booking list validation error: ' . json_encode($e->errors()));
            return response()->json([
                'status' => 'error',
                'message' => 'Parameter tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Booking list error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memuat daftar booking'
            ], 500);
        }
    }

    /**
     * Get details for a specific booking
     * 
     * @param int $id Booking ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            // Find booking by ID
            $booking = Booking::with('field')
                ->where('id', $id)
                ->where('user_id', Auth::id()) // Ensure user can only see their own bookings
                ->firstOrFail();
                
            return response()->json([
                'status' => 'success',
                'data' => $booking
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Booking tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Booking show error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memuat detail booking'
            ], 500);
        }
    }

    /**
     * Create a new booking
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Validate incoming request
            $validatedData = $request->validate([
                'field_id' => 'required|integer|exists:fields,id',
                'date' => 'required|date|after_or_equal:today',
                'start_time' => 'required|date_format:H:i',
                'duration' => 'required|integer|min:1|max:4',
                'notes' => 'nullable|string|max:500',
            ]);

            // Calculate end time
            $startTime = $request->start_time;
            $duration = $request->duration;
            $endTimeHour = intval(substr($startTime, 0, 2)) + $duration;
            $endTime = ($endTimeHour < 10 ? '0' : '') . $endTimeHour . ':00';
            
            // Get field for price calculation
            $field = Field::findOrFail($request->field_id);
            
            // Calculate total price
            $totalPrice = $field->price * $duration;
            
            // Generate a unique booking code
            $bookingCode = 'LK' . Str::upper(Str::random(8));
            
            // Create and save booking
            $booking = new Booking();
            $booking->field_id = $request->field_id;
            $booking->user_id = Auth::id();
            $booking->date = $request->date;
            $booking->start_time = $startTime;
            $booking->end_time = $endTime;
            $booking->duration = $duration;
            $booking->total_price = $totalPrice;
            $booking->status = 'pending';
            $booking->payment_status = 'unpaid';
            $booking->booking_code = $bookingCode;
            $booking->notes = $request->notes;
            $booking->save();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Booking berhasil dibuat',
                'data' => [
                    'booking_id' => $booking->id,
                    'booking_code' => $booking->booking_code,
                    'redirect_to' => route('bookings.show', $booking->id)
                ]
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data booking tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Booking creation error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat booking. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Cancel a booking
     * 
     * @param int $id Booking ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel($id)
    {
        try {
            // Find booking by ID
            $booking = Booking::where('id', $id)
                ->where('user_id', Auth::id()) // Ensure user can only cancel their own bookings
                ->where('status', '!=', 'cancelled') // Can't cancel already cancelled bookings
                ->firstOrFail();
            
            // Update booking status
            $booking->status = 'cancelled';
            $booking->save();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Booking berhasil dibatalkan'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Booking tidak ditemukan atau tidak dapat dibatalkan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Booking cancellation error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membatalkan booking'
            ], 500);
        }
    }
}
