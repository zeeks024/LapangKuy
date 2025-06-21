<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmation;
use App\Mail\BookingCancellation;
use App\Mail\NewBookingNotification;
use App\Models\Booking;
use App\Models\Field;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{    public function index()
    {
        // Get all bookings for the authenticated user from database
        $bookings = Booking::where('user_id', auth()->id())
            ->with('field')  // Eager load the field relationship
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Transform bookings to include field name for the view
        $bookings->getCollection()->transform(function ($booking) {
            // Add field name to the booking if field exists
            if ($booking->field) {
                $booking->field_name = $booking->field->name;
            } else {
                $booking->field_name = 'Lapangan ' . $booking->field_id;
            }
            
            return $booking;
        });
        
        return view('bookings.index', compact('bookings'));
    }public function show($id)
    {
        try {
            // Get the booking from database
            $booking = Booking::find($id);
            
            if (!$booking) {
                // Booking tidak ditemukan, redirect ke halaman error
                return redirect()->route('bookings.not-found');
            }
              // Check if user is authorized to view this booking
            if ($booking->user_id !== auth()->id()) {
                return redirect()->route('bookings.index')->with('error', 'Anda tidak memiliki akses untuk melihat booking ini.');
            }
            
            // Get the field information
            $field = $booking->field;
            
            // Add field information to the booking
            if ($field) {
                $booking->field_name = $field->name;
                $booking->field_image = $field->image_url ?? 'https://placehold.co/400x200/043E03/FFFFFF?text=Lapangan+'.$booking->field_id;
                $booking->field_location = $field->location;
            } else {
                $booking->field_name = 'Lapangan '.$booking->field_id;
                $booking->field_image = 'https://placehold.co/400x200/043E03/FFFFFF?text=Lapangan+'.$booking->field_id;
                $booking->field_location = 'Jakarta';
            }
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error in BookingController@show: ' . $e->getMessage());
            
            // Create a fallback booking object
            $booking = (object)[
                'id' => $id,
                'field_name' => 'Lapangan (Error)',
                'field_id' => 0,
                'date' => date('Y-m-d'),
                'start_time' => '00:00',
                'end_time' => '00:00',
                'duration' => 0,
                'total_price' => 0,
                'status' => 'error',
                'payment_status' => 'error',
                'created_at' => date('Y-m-d H:i:s'),
                'field_image' => 'https://placehold.co/400x200/FF0000/FFFFFF?text=Error',
                'field_location' => 'Error loading booking',
                'booking_code' => 'ERROR'
            ];
        }
        
        return view('bookings.show', compact('booking'));
    }    public function create($field_id)
    {
        $field = Field::findOrFail($field_id);
        $available_slots = [
            '08:00', '09:00', '10:00', '11:00',
            '12:00', '13:00', '14:00', '15:00',
            '16:00', '17:00', '18:00', '19:00', '20:00', '21:00'
        ];
        // Default tanggal booking: besok
        $date = request('date', date('Y-m-d', strtotime('+1 day')));
        $booked_slots = \App\Models\Booking::where('field_id', $field_id)
            ->where('date', $date)
            ->where('status', '!=', 'cancelled')
            ->where('payment_status', '!=', 'failed')
            ->pluck('start_time')
            ->toArray();
        return view('bookings.create', compact('field', 'available_slots', 'booked_slots'));
    }    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'field_id' => 'required|integer',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'duration' => 'required|integer|min:1|max:4',
        ]);
        
        // Generate a unique booking code
        $booking_code = 'LK' . strtoupper(substr(md5(time()), 0, 8));
          // Calculate end time based on start time and duration
        $start_time = $request->start_time;
        $duration = $request->duration;
        $end_time_hour = intval(substr($start_time, 0, 2)) + $duration;
        $end_time = ($end_time_hour < 10 ? '0' : '') . $end_time_hour . ':00';
        
        try {
            // Get field for price calculation
            $field = Field::find($request->field_id);
            
            if (!$field) {
                return back()->withErrors(['field_id' => 'Lapangan tidak ditemukan.']);
            }
            
            // Get actual field price
            $field_price = $field->price;
            
            // Calculate total price
            $total_price = $field_price * $duration;
            
            // Create and save the booking to database
            $booking = new Booking();
            $booking->field_id = $request->field_id;
            $booking->user_id = auth()->id();
            $booking->date = $request->date;
            $booking->start_time = $start_time;
            $booking->end_time = $end_time;
            $booking->duration = $duration;
            $booking->total_price = $total_price;
            $booking->status = 'pending';
            $booking->payment_status = 'unpaid';
            $booking->booking_code = $booking_code;
            $booking->transaction_id = null;
            $booking->payment_token = null;
            $booking->save();

            // Create booking object for email
            $bookingEmail = (object) [
                'id' => $booking->id,
                'booking_code' => $booking_code,
                'date' => $request->date,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'duration' => $duration,
                'total_price' => $total_price,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'user' => (object) [
                    'name' => auth()->user()->name ?? 'Demo User',
                    'email' => auth()->user()->email ?? 'demo@example.com',
                    'phone' => auth()->user()->phone ?? '081234567890'
                ],                'field' => (object) [
                    'name' => $field ? $field->name : 'Lapangan ' . $request->field_id,
                    'location' => $field ? $field->location : 'Semarang, Kec. Gn. Pati',
                    'price' => $field_price,
                    'contact_phone' => $field ? $field->contact_phone : '021-12345678',
                    'owner' => (object) [
                        'name' => 'Pemilik Lapangan',
                        'email' => 'owner@example.com'
                    ]
                ],
                'contact_name' => auth()->user()->name ?? 'Demo User',
                'contact_phone' => auth()->user()->phone ?? '081234567890',
                'created_at' => now()
            ];

            // Send booking confirmation email to customer
            if (config('mail.default') !== 'log') {
                Mail::to($bookingEmail->user->email)->send(new BookingConfirmation($bookingEmail));
                
                // Send new booking notification to field owner
                Mail::to($bookingEmail->field->owner->email)->send(new NewBookingNotification($bookingEmail));
            }
        } catch (\Exception $e) {
            // Log error but don't prevent booking creation
            \Log::error('Error in booking creation: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat booking. Silakan coba lagi.');
        }
          return redirect()->route('bookings.show', $booking->id)->with('success', 'Booking berhasil dibuat! Silahkan lakukan pembayaran.');
    }

    /**
     * Cancel a booking.
     */
    /**
     * Confirm a booking - admin function
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirm($id)
    {
        try {
            // Fetch the booking from database
            $booking = Booking::with(['field', 'user'])->find($id);
            if (!$booking) {
                return redirect()->route('admin.bookings')->with('error', 'Booking tidak ditemukan.');
            }
            
            // Check if booking can be confirmed (only pending bookings)
            if ($booking->status !== 'pending') {
                return redirect()->route('admin.bookings')->with('error', 'Hanya booking dengan status pending yang dapat dikonfirmasi.');
            }
            
            // Update booking status to confirmed
            $booking->update([
                'status' => 'confirmed'
            ]);
            
            // Send booking confirmation email to customer
            if (config('mail.default') !== 'log') {
                Mail::to($booking->user->email)->send(new BookingConfirmation($booking));
            }
            
            return redirect()->route('admin.bookings')->with('success', 'Booking berhasil dikonfirmasi.');
        } catch (\Exception $e) {
            // Log error and show error message
            \Log::error('Error confirming booking: ' . $e->getMessage());
            return redirect()->route('admin.bookings')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Cancel a booking
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel($id)
    {
        try {
            // Fetch the booking from database
            $booking = Booking::with(['field', 'user'])->find($id);
            if (!$booking) {
                return redirect()->route('bookings.index')->with('error', 'Booking tidak ditemukan.');
            }
            
            // For admin users, skip the user check
            if (auth()->user()->role !== 'admin') {
                // Check if user is authorized to cancel this booking
                if ($booking->user_id !== auth()->id()) {
                    return redirect()->route('bookings.index')->with('error', 'Anda tidak memiliki akses untuk membatalkan booking ini.');
                }
            }
            
            // Check if booking can be cancelled (only pending or confirmed bookings)
            if (!in_array($booking->status, ['pending', 'confirmed'])) {
                return redirect()->back()->with('error', 'Booking ini tidak dapat dibatalkan karena statusnya sudah ' . $booking->status);
            }
            
            // Update booking status to cancelled
            $booking->update([
                'status' => 'cancelled',
                'payment_status' => $booking->payment_status === 'paid' ? 'refunded' : 'cancelled'
            ]);
            
            // Send cancellation email to customer
            if (config('mail.default') !== 'log') {
                Mail::to($booking->user->email)->send(new BookingCancellation($booking));
            }} catch (\Exception $e) {
            // Log error and show error message
            \Log::error('Error cancelling booking: ' . $e->getMessage());
            return redirect()->route('bookings.index')->with('error', 'Terjadi kesalahan saat membatalkan booking.');
        }

        // Flash success message
        return redirect()->route('bookings.index')->with('success', 'Booking berhasil dibatalkan.');
    }
    
    /**
     * Show the form for editing a booking.
     */    public function edit($id)
    {
        // Fetch the booking from database
        $booking = Booking::with('field')->find($id);
          if (!$booking) {
            return redirect()->route('bookings.index')->with('error', 'Booking tidak ditemukan.');
        }
        
        // Check if user is authorized to edit this booking
        if ($booking->user_id !== auth()->id()) {
            return redirect()->route('bookings.index')->with('error', 'Anda tidak memiliki akses untuk mengedit booking ini.');
        }
        
        // Check if user can edit this booking (only pending bookings can be edited)
        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.show', $id)
                           ->with('error', 'Booking ini tidak dapat diedit karena statusnya sudah ' . $booking->status);
        }
        
        // Fetch available time slots (in a real app, this would be dynamic based on existing bookings)
        $available_slots = [
            '08:00', '09:00', '10:00', '11:00',
            '12:00', '13:00', '14:00', '15:00',
            '16:00', '17:00', '18:00', '19:00', '20:00', '21:00'
        ];
        
        // Get booked slots for the selected date (excluding current booking)
        $booked_slots = Booking::where('field_id', $booking->field_id)
            ->where('date', $booking->date)
            ->where('id', '!=', $booking->id)
            ->where('status', '!=', 'cancelled')
            ->pluck('start_time')
            ->toArray();
        
        return view('bookings.edit', compact('booking', 'available_slots', 'booked_slots'));
    }

    /**
     * Update a booking.
     */    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'duration' => 'required|integer|min:1|max:4',
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:500',
        ]);
          // Fetch the booking from database
        $booking = Booking::with('field')->find($id);
        
        if (!$booking) {
            return redirect()->route('bookings.index')->with('error', 'Booking tidak ditemukan.');
        }
        
        // Check if user is authorized to update this booking
        if ($booking->user_id !== auth()->id()) {
            return redirect()->route('bookings.index')->with('error', 'Anda tidak memiliki akses untuk mengedit booking ini.');
        }
        
        // Check if booking can be updated (only pending bookings)
        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.show', $id)
                           ->with('error', 'Booking ini tidak dapat diedit karena statusnya sudah ' . $booking->status);
        }
        
        // Check if the new time slot is available (excluding current booking)
        $conflictingBooking = Booking::where('field_id', $booking->field_id)
            ->where('date', $request->date)
            ->where('start_time', $request->start_time)
            ->where('id', '!=', $booking->id)
            ->where('status', '!=', 'cancelled')
            ->first();
            
        if ($conflictingBooking) {
            return redirect()->back()
                           ->with('error', 'Waktu yang dipilih sudah dibooking oleh orang lain.')
                           ->withInput();
        }
        
        // Calculate end time and total price
        $startTime = \Carbon\Carbon::createFromFormat('H:i', $request->start_time);
        $endTime = $startTime->copy()->addHours($request->duration);
        $totalPrice = $booking->field->price * $request->duration;
        
        // Update the booking
        $booking->update([
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $endTime->format('H:i'),
            'duration' => $request->duration,
            'total_price' => $totalPrice,
            'contact_name' => $request->contact_name,
            'contact_phone' => $request->contact_phone,
            'notes' => $request->notes,
        ]);
        
        return redirect()->route('bookings.show', $id)
                        ->with('success', 'Booking berhasil diperbarui!');
    }/**
     * API endpoint to check booking availability
     */
    public function checkAvailability(Request $request)
    {
        try {            $request->validate([
                'field_id' => 'required|integer|exists:fields,id',
                'date' => 'required|date|after_or_equal:today',
                'start_time' => 'required|date_format:H:i',
                'duration' => 'required|integer|min:1|max:8'
            ]);

            $fieldId = $request->field_id;
            $date = $request->date;
            $startTime = $request->start_time;
            $duration = $request->duration;

            // Check if field exists and operating hours
            $field = \App\Models\Field::find($fieldId);
            if (!$field) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Lapangan tidak ditemukan'
                ], 404);
            }

            // Validate operating hours
            $startHour = intval(substr($startTime, 0, 2));
            $openHour = intval(substr($field->open_time, 0, 2));
            $closeHour = intval(substr($field->close_time, 0, 2));

            if ($startHour < $openHour || ($startHour + $duration) > $closeHour) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => ['start_time' => ['Jam booking di luar jam operasional lapangan']]
                ], 422);
            }

            // Calculate end time
            $endHour = $startHour + $duration;
            $endTime = ($endHour < 10 ? '0' : '') . $endHour . ':00';            // Check availability
            $available = $this->checkTimeSlotAvailability($fieldId, $date, $startTime, $duration);
            
            $responseData = [
                'available' => $available,
                'field' => [
                    'id' => $field->id,
                    'name' => $field->name,
                    'price' => $field->price
                ],
                'booking_details' => [
                    'date' => $date,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'duration' => $duration,
                    'total_price' => $field->price * $duration
                ]
            ];

            if (!$available) {
                $responseData['conflicts'] = $this->getConflictingBookings($fieldId, $date, $startTime, $duration);
            }

            return response()->json([
                'status' => 'success',
                'data' => $responseData
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan dalam memeriksa ketersediaan'
            ], 500);
        }
    }    /**
     * Check if time slot is available for booking
     */
    private function checkTimeSlotAvailability($fieldId, $date, $startTime, $duration)
    {
        $startHour = intval(substr($startTime, 0, 2));
        $endHour = $startHour + $duration;
        $endTime = ($endHour < 10 ? '0' : '') . $endHour . ':00';
        
        // Check for existing bookings that overlap with requested time
        $conflictingBookings = Booking::where('field_id', $fieldId)
            ->where('date', $date)
            ->where('status', '!=', 'cancelled')
            ->where(function($query) use ($startTime, $endTime) {
                $query->where(function($q) use ($startTime, $endTime) {
                    // Booking starts before our end time and ends after our start time
                    $q->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
                });
            })
            ->exists();
            
        return !$conflictingBookings; // Available if no conflicts
    }    /**
     * Get conflicting bookings for the requested time slot
     */
    private function getConflictingBookings($fieldId, $date, $startTime, $duration)
    {
        $startHour = intval(substr($startTime, 0, 2));
        $endHour = $startHour + $duration;
        $endTime = ($endHour < 10 ? '0' : '') . $endHour . ':00';
        
        try {
            // Query actual conflicting bookings from database
            $conflictingBookings = Booking::where('field_id', $fieldId)
                ->where('date', $date)
                ->where('status', '!=', 'cancelled')
                ->where('payment_status', '!=', 'failed')
                ->where(function($query) use ($startTime, $endTime) {
                    $query->where(function($q) use ($startTime, $endTime) {
                        // Booking starts before our end time and ends after our start time
                        $q->where('start_time', '<', $endTime)
                          ->where('end_time', '>', $startTime);
                    });
                })
                ->select('id', 'start_time', 'end_time', 'status', 'payment_status')
                ->get();
            
            $conflicts = [];
            foreach ($conflictingBookings as $booking) {
                // Calculate duration for each conflicting booking
                $bookingStartHour = intval(substr($booking->start_time, 0, 2));
                $bookingEndHour = intval(substr($booking->end_time, 0, 2));
                $bookingDuration = $bookingEndHour - $bookingStartHour;
                
                $conflicts[] = [
                    'booking_id' => 'LK' . str_pad($booking->id, 6, '0', STR_PAD_LEFT),
                    'start_time' => $booking->start_time,
                    'end_time' => $booking->end_time,
                    'duration' => $bookingDuration,
                    'status' => $booking->status ?: 'confirmed'
                ];
            }
            
            return $conflicts;
            
        } catch (\Exception $e) {
            // If database query fails, return empty conflicts array
            // In production, you might want to log this error
            \Log::error('Failed to get conflicting bookings: ' . $e->getMessage());
            
            // Return fallback simulated conflict for demo purposes
            return [
                [
                    'booking_id' => 'LK' . strtoupper(substr(md5(rand()), 0, 8)),
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'duration' => $duration,
                    'status' => 'confirmed'
                ]
            ];
        }
    }

    public function reviewableBookings()
    {
        // Gunakan waktu server Indonesia (WIB)
        $now = \Carbon\Carbon::now('Asia/Jakarta');
        \App\Models\Booking::whereIn('status', ['pending', 'confirmed'])
            ->where('end_time', '<', $now->format('H:i'))
            ->where('date', '<=', $now->format('Y-m-d'))
            ->update(['status' => 'completed']);

        $reviewableBookings = \App\Models\Booking::where('user_id', auth()->id())
        ->where('status', 'completed')
        ->whereDoesntHave('review')
        ->with('field')
        ->get();
        return view('reviews.reviewable-bookings', compact('reviewableBookings'));
    }
}
