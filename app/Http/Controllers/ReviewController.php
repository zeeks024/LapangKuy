<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Field;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Auth::user()->reviews()->with('field')->latest()->paginate(10);
        return view('reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $bookingId = $request->get('booking_id');
        $fieldId = $request->get('field_id');
        
        if ($bookingId) {
            // Create review from specific booking
            $booking = Booking::where('id', $bookingId)
                ->where('user_id', Auth::id())
                ->where('status', 'completed')
                ->with('field')
                ->first();
                
            if (!$booking) {
                return redirect()->back()->with('error', 'Booking tidak ditemukan atau belum selesai.');
            }
            
            if ($booking->hasReview()) {
                return redirect()->route('reviews.edit', $booking->review)
                    ->with('info', 'Anda sudah memberikan review untuk booking ini.');
            }
            
            return view('reviews.create', compact('booking'));
        }
        
        if ($fieldId) {
            // Create review from field (legacy)
            $field = Field::findOrFail($fieldId);
            
            // Check if user has completed booking for this field
            $hasCompletedBooking = Booking::where('user_id', Auth::id())
                ->where('field_id', $field->id)
                ->where('status', 'completed')
                ->exists();

            if (!$hasCompletedBooking) {
                return redirect()->back()->with('error', 'Anda hanya dapat memberikan review setelah menyelesaikan booking di lapangan ini.');
            }

            return view('reviews.create', compact('field'));
        }
        
        return redirect()->back()->with('error', 'Parameter tidak valid.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'field_id' => 'required|exists:fields,id',
            'booking_id' => 'nullable|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        if ($request->booking_id) {
            // Review from specific booking
            $booking = Booking::where('id', $request->booking_id)
                ->where('user_id', Auth::id())
                ->where('status', 'completed')
                ->first();
                
            if (!$booking) {
                return redirect()->back()->with('error', 'Booking tidak valid atau belum selesai.');
            }
            
            if ($booking->hasReview()) {
                return redirect()->back()->with('error', 'Anda sudah memberikan review untuk booking ini.');
            }
            
            Review::create([
                'user_id' => Auth::id(),
                'field_id' => $request->field_id,
                'booking_id' => $request->booking_id,
                'rating' => $request->rating,
                'comment' => $request->comment
            ]);
        } else {
            // Legacy review (field-based)
            $hasCompletedBooking = Booking::where('user_id', Auth::id())
                ->where('field_id', $request->field_id)
                ->where('status', 'completed')
                ->exists();

            if (!$hasCompletedBooking) {
                return redirect()->back()->with('error', 'Anda hanya dapat memberikan review setelah menyelesaikan booking di lapangan ini.');
            }

            // Check if review already exists for this field
            $existingReview = Review::where('user_id', Auth::id())
                ->where('field_id', $request->field_id)
                ->whereNull('booking_id')
                ->first();

            if ($existingReview) {
                return redirect()->back()->with('error', 'Anda sudah memberikan review untuk lapangan ini.');
            }

            Review::create([
                'user_id' => Auth::id(),
                'field_id' => $request->field_id,
                'rating' => $request->rating,
                'comment' => $request->comment
            ]);
        }

        return redirect()->route('fields.show', $request->field_id)
            ->with('success', 'Review berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        return view('reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            return redirect()->route('reviews.index')->with('error', 'Anda tidak memiliki akses untuk mengedit review ini.');
        }

        return view('reviews.edit', compact('review'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            return redirect()->route('reviews.index')->with('error', 'Anda tidak memiliki akses untuk mengedit review ini.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return redirect()->route('fields.show', $review->field_id)
            ->with('success', 'Review berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        if ($review->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return redirect()->route('reviews.index')->with('error', 'Anda tidak memiliki akses untuk menghapus review ini.');
        }

        $fieldId = $review->field_id;
        $review->delete();

        return redirect()->route('fields.show', $fieldId)
            ->with('success', 'Review berhasil dihapus!');
    }

    /**
     * Show form to create review from booking
     */
    public function createFromBooking(Booking $booking)
    {
        // Load the field relationship
        $booking->load('field');
        
        // Verify booking belongs to authenticated user and is completed
        if ($booking->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Booking tidak ditemukan.');
        }
        
        if ($booking->status !== 'completed') {
            return redirect()->back()->with('error', 'Anda hanya dapat memberikan review setelah booking selesai.');
        }
        
        if ($booking->hasReview()) {
            return redirect()->route('reviews.edit', $booking->review)
                ->with('info', 'Anda sudah memberikan review untuk booking ini.');
        }
        
        // Additional check for field existence
        if (!$booking->field) {
            return redirect()->back()->with('error', 'Data lapangan tidak ditemukan.');
        }
        
        return view('reviews.create-from-booking', compact('booking'));
    }

    /**
     * Get reviewable bookings for user
     */
    public function reviewableBookings()
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        // Create a basic array for testing
        $reviewableBookings = collect([]);
        
        try {
            // Get the actual bookings
            $bookings = Booking::where('user_id', Auth::id())
                ->where('status', 'completed')
                ->whereDoesntHave('review')
                ->with(['field'])
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Create a manual pagination-like object
            $currentPage = request()->get('page', 1);
            $perPage = 10;
            $total = $bookings->count();
            
            $reviewableBookings = new \Illuminate\Pagination\LengthAwarePaginator(
                $bookings->forPage($currentPage, $perPage),
                $total,
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'pageName' => 'page']
            );
            
        } catch (\Exception $e) {
            \Log::error('Error in reviewableBookings: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
            
        return view('reviews.reviewable-bookings', compact('reviewableBookings'));
    }

    /**
     * Get field reviews with pagination
     */
    public function fieldReviews(Field $field)
    {
        $reviews = Review::where('field_id', $field->id)
            ->with(['user', 'booking'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $averageRating = Review::averageRating($field->id);
        $totalReviews = $reviews->total();
        
        return view('reviews.field-reviews', compact('field', 'reviews', 'averageRating', 'totalReviews'));
    }
}
