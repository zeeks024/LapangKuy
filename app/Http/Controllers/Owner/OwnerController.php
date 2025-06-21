<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Field;
use App\Models\Booking;
use App\Models\User;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OwnerController extends Controller
{
    /**
     * Image upload service
     * 
     * @var ImageUploadService
     */
    protected $imageUploadService;

    /**
     * Create a new controller instance.
     *
     * @param ImageUploadService $imageUploadService
     * @return void
     */
    public function __construct(ImageUploadService $imageUploadService)
    {
        $this->imageUploadService = $imageUploadService;
    }

    public function dashboard()
    {
        $user = Auth::user();
        $fields = $user->ownedFields()->with(['bookings', 'reviews'])->get();
        $totalFields = $fields->count();
        $totalBookings = $fields->sum(function($field) {
            return $field->bookings->count();
        });
        $totalRevenue = $fields->sum(function($field) {
            return $field->bookings->where('status', 'confirmed')->sum('total_price');
        });
        
        // Calculate average rating across all fields
        $totalReviews = 0;
        $sumRatings = 0;
        foreach ($fields as $field) {
            $fieldReviews = $field->reviews->count();
            $totalReviews += $fieldReviews;
            $sumRatings += $field->reviews->sum('rating');
        }
        $avgRating = $totalReviews > 0 ? round($sumRatings / $totalReviews, 1) : 0;
        
        // Get recent bookings
        $recentBookings = Booking::whereIn('field_id', $fields->pluck('id'))
                            ->with(['user', 'field'])
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();
        
        // Get booking statistics for the past 7 days
        $bookingStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = Booking::whereIn('field_id', $fields->pluck('id'))
                    ->whereDate('created_at', $date)
                    ->count();
            $bookingStats[] = [
                'date' => now()->subDays($i)->format('d M'),
                'count' => $count
            ];
        }
        
        // Get field category distribution
        $categoryStats = $fields->groupBy('category')
            ->map(function ($items, $category) {
                return [
                    'category' => $category ?: 'Uncategorized',
                    'count' => $items->count()
                ];
            })->values()->toArray();

        return view('owner.dashboard', compact(
            'fields', 
            'totalFields', 
            'totalBookings', 
            'totalRevenue', 
            'avgRating', 
            'totalReviews',
            'recentBookings',
            'bookingStats',
            'categoryStats'
        ));
    }

    public function fields()
    {
        $fields = Auth::user()->ownedFields()->paginate(10);
        return view('owner.fields.index', compact('fields'));
    }

    public function createField()
    {
        return view('owner.fields.create');
    }

    public function storeField(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image_url' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'open_time' => 'required|string',
            'close_time' => 'required|string',
            'facilities' => 'nullable|array',
            'promo' => 'boolean'
        ]);

        // Handle image upload using the image upload service
        $image_url = $this->imageUploadService->uploadImage(
            $request->file('image'), 
            'fields', 
            $request->image_url
        );
        
        // Handle gallery images upload
        $gallery = $this->imageUploadService->uploadMultipleImages(
            $request->file('gallery'),
            'fields/gallery',
            null
        );

        // Set the validated data
        $validated['owner_id'] = Auth::id();
        $validated['facilities'] = $validated['facilities'] ?? [];
        $validated['promo'] = $request->has('promo');
        $validated['image_url'] = $image_url;
        $validated['gallery'] = $gallery;

        Field::create($validated);

        return redirect()->route('owner.fields')->with('success', 'Lapangan berhasil ditambahkan!');
    }

    public function editField(Field $field)
    {
        // Check if user owns this field
        if ($field->owner_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return redirect()->route('owner.fields')->with('error', 'Anda tidak memiliki akses ke lapangan ini.');
        }

        return view('owner.fields.edit', compact('field'));
    }

    public function updateField(Request $request, Field $field)
    {
        // Check if user owns this field
        if ($field->owner_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return redirect()->route('owner.fields')->with('error', 'Anda tidak memiliki akses ke lapangan ini.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image_url' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'open_time' => 'required|string',
            'close_time' => 'required|string',
            'facilities' => 'nullable|array',
            'promo' => 'boolean'
        ]);

        // Handle image upload using the image upload service
        $image_url = $this->imageUploadService->uploadImage(
            $request->file('image'), 
            'fields', 
            $request->image_url ?? $field->image_url
        );
        
        // Handle gallery images upload
        $currentGallery = $field->gallery ?? [];
        $gallery = $this->imageUploadService->uploadMultipleImages(
            $request->file('gallery'),
            'fields/gallery',
            $currentGallery
        );

        // Set the validated data
        $validated['facilities'] = $validated['facilities'] ?? [];
        $validated['promo'] = $request->has('promo');
        $validated['image_url'] = $image_url;
        $validated['gallery'] = $gallery;

        $field->update($validated);

        return redirect()->route('owner.fields')->with('success', 'Lapangan berhasil diperbarui!');
    }

    public function deleteField(Field $field)
    {
        // Check if user owns this field
        if ($field->owner_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return redirect()->route('owner.fields')->with('error', 'Anda tidak memiliki akses ke lapangan ini.');
        }

        $field->delete();

        return redirect()->route('owner.fields')->with('success', 'Lapangan berhasil dihapus!');
    }

    public function bookings()
    {
        $user = Auth::user();
        $fieldIds = $user->ownedFields->pluck('id');
        $bookings = Booking::whereIn('field_id', $fieldIds)
                          ->with(['field', 'user'])
                          ->orderBy('created_at', 'desc')
                          ->paginate(15);

        return view('owner.bookings.index', compact('bookings'));
    }

    public function updateBookingStatus(Request $request, Booking $booking)
    {
        $user = Auth::user();
        $fieldIds = $user->ownedFields->pluck('id');
        
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled'
        ]);

        $booking->update($validated);

        return redirect()->route('owner.bookings')->with('success', 'Status booking berhasil diperbarui!');
    }
}
