<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Review;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FieldController extends Controller
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

    public function index(Request $request)
    {
        $query = Field::query();

        // Search by name or location
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category/sport type
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by location
        if ($request->filled('location_filter')) {
            $query->where('location', 'like', "%{$request->location_filter}%");
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popular':
                // Order by most bookings (would need a join with bookings table)
                $query->withCount('bookings')->orderBy('bookings_count', 'desc');
                break;
            default:
                $query->orderBy($sortBy, $sortOrder);
        }

        // Get fields with pagination
        $fields = $query->paginate(9)->withQueryString();
        
        // Get categories for filter dropdown
        $categories = Field::select('category')
                          ->distinct()
                          ->pluck('category')
                          ->filter()
                          ->sort();

        // Get unique locations for filter
        $locations = Field::select('location')
                         ->distinct()
                         ->pluck('location')
                         ->map(function($location) {
                             // Extract city from full address
                             $parts = explode(',', $location);
                             return trim(end($parts));
                         })
                         ->unique()
                         ->filter()
                         ->sort()
                         ->values();

        return view('fields.index', compact('fields', 'categories', 'locations'));
    }

    public function search(Request $request)
    {
        // Redirect to index with search parameters
        return redirect()->route('fields.index', $request->all());
    }    public function show($id)
    {
        $field = Field::with(['reviews.user', 'bookings', 'owner'])->findOrFail($id);
        
        // Set default values jika field kosong
        if (empty($field->capacity)) {
            $field->capacity = '22';
        }
        if (empty($field->size)) {
            $field->size = 'Standar FIFA';
        }
        if (empty($field->surface)) {
            $field->surface = 'Rumput Sintetis';
        }
        if (empty($field->open_time)) {
            $field->open_time = '08:00';
        }
        if (empty($field->close_time)) {
            $field->close_time = '22:00';
        }
        
        // Calculate average rating
        $averageRating = $field->reviews()->avg('rating') ?? 0;
        $totalReviews = $field->reviews()->count();
        
        // Get similar fields (same category, different field)
        $similarFields = Field::where('category', $field->category)
                             ->where('id', '!=', $field->id)
                             ->limit(4)
                             ->get();
                             
        // Jika tidak ada similar fields, ambil beberapa field lain
        if ($similarFields->count() == 0) {
            $similarFields = Field::where('id', '!=', $field->id)
                                 ->inRandomOrder()
                                 ->limit(4)
                                 ->get();
        }
        
        // Get available time slots for today and tomorrow
        $today = now()->toDateString();
        $tomorrow = now()->addDay()->toDateString();
        $todaySlots = $this->getAvailableTimeSlots($field, $today);
        $tomorrowSlots = $this->getAvailableTimeSlots($field, $tomorrow);
        
        // Calculate available slots count
        $availableTodayCount = collect($todaySlots)->where('available', true)->count();
        $availableTomorrowCount = collect($tomorrowSlots)->where('available', true)->count();
        
        // Memastikan fasilitas tersedia
        if (empty($field->facilities)) {
            $field->facilities = [
                'Toilet',
                'Parkir',
                'WiFi',
                'Kamar Ganti',
                'Tribun',
                'CCTV'
            ];
        }
        
        // Create schedule data
        $schedule = [
            'today' => [
                'date' => $today,
                'day' => now()->format('l'),
                'display_date' => now()->format('d M Y'),
                'slots' => $todaySlots,
                'available' => $availableTodayCount
            ],
            'tomorrow' => [
                'date' => $tomorrow,
                'day' => now()->addDay()->format('l'),
                'display_date' => now()->addDay()->format('d M Y'),
                'slots' => $tomorrowSlots,
                'available' => $availableTomorrowCount
            ]
        ];
        
        // Log for debugging
        \Log::info('Field details loaded', [
            'field_id' => $id,
            'field_name' => $field->name,
            'has_image' => !empty($field->image_url),
            'reviews_count' => $totalReviews,
            'today_slots_count' => count($todaySlots),
            'similar_fields_count' => $similarFields->count()
        ]);
        
        // Ambil semua review untuk lapangan ini
        $reviews = $field->reviews;
        
        return view('fields.show', compact(
            'field', 
            'averageRating', 
            'totalReviews', 
            'similarFields',
            'schedule',
            'todaySlots',
            'reviews' // tambahkan reviews ke compact
        ));
    }

    public function create()
    {
        return view('fields.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'open_time' => 'required',
            'close_time' => 'required',
            'description' => 'required|string',
            'image_url' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'facilities' => 'nullable|array',
        ]);

        // Use the facilities array directly, Laravel will handle JSON conversion through casting
        $facilities = $request->facilities ?: [];
        
        // Handle image upload using the image upload service
        $image_url = $this->imageUploadService->uploadImage(
            $request->file('image'),
            'fields',
            $request->image_url
        );
        
        Field::create([
            'name' => $request->name,
            'location' => $request->location,
            'price' => $request->price,
            'category' => $request->category,
            'open_time' => $request->open_time,
            'close_time' => $request->close_time,
            'description' => $request->description,
            'facilities' => $facilities,
            'image_url' => $image_url,
            'promo' => $request->has('promo'),
            'owner_id' => auth()->id(), // Set current user as owner
        ]);
        
        return redirect()->route('fields.index')->with('success', 'Lapangan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $field = Field::findOrFail($id);
        
        // Check if user can edit this field
        if (auth()->user()->role !== 'admin' && $field->owner_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        
        return view('fields.edit', compact('field'));
    }

    public function update(Request $request, $id)
    {
        $field = Field::findOrFail($id);
        
        // Check if user can edit this field
        if (auth()->user()->role !== 'admin' && $field->owner_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'open_time' => 'required',
            'close_time' => 'required',
            'description' => 'required|string',
            'image_url' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'facilities' => 'nullable|array',
        ]);
          
        // Use the facilities array directly, Laravel will handle JSON conversion through casting
        $facilities = $request->facilities ?: [];
        
        // Handle image upload using the image upload service
        $image_url = $this->imageUploadService->uploadImage(
            $request->file('image'),
            'fields',
            $request->image_url ?? $field->image_url
        );
        
        $field->update([
            'name' => $request->name,
            'location' => $request->location,
            'price' => $request->price,
            'category' => $request->category,
            'open_time' => $request->open_time,
            'close_time' => $request->close_time,
            'description' => $request->description,
            'facilities' => $facilities,
            'image_url' => $image_url,
            'promo' => $request->has('promo'),
        ]);
        
        return redirect()->route('fields.show', $id)->with('success', 'Lapangan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $field = Field::findOrFail($id);
        
        // Check if user can delete this field
        if (auth()->user()->role !== 'admin' && $field->owner_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        
        $field->delete();
        
        return redirect()->route('fields.index')->with('success', 'Lapangan berhasil dihapus!');
    }    /**
     * Get available time slots for a field on a specific date
     */
    private function getAvailableTimeSlots($field, $date)
    {
        // This is a simplified version - in a real app you'd check against bookings
        $slots = [];
        
        // Ensure open_time and close_time are not null
        $openTime = strtotime($field->open_time ?? '08:00');
        $closeTime = strtotime($field->close_time ?? '22:00');
        
        // Get booked slots from bookings
        $bookedHours = [];
        $fieldBookings = $field->bookings()
            ->where('date', $date)
            ->where(function($query) {
                $query->where('status', 'confirmed')
                      ->orWhere('status', 'completed');
            })
            ->get();
        
        // Extract booked hours
        foreach ($fieldBookings as $booking) {
            $startHour = intval(substr($booking->start_time, 0, 2));
            $endHour = intval(substr($booking->end_time, 0, 2));
            
            for ($h = $startHour; $h < $endHour; $h++) {
                $bookedHours[] = $h;
            }
        }
        
        // Generate all time slots with availability information
        for ($time = $openTime; $time < $closeTime; $time += 3600) { // 1 hour intervals
            $hour = (int)date('H', $time);
            $timeStart = date('H:i', $time);
            $timeEnd = date('H:i', $time + 3600);
            
            // Check if this hour is booked
            $isBooked = in_array($hour, $bookedHours);
            
            // If we don't have real booking data yet, use random availability
            if (count($fieldBookings) === 0) {
                $available = rand(0, 1) > 0.3; // 70% probabilitas tersedia
            } else {
                $available = !$isBooked;
            }
            
            $slots[] = [
                'time' => "$timeStart - $timeEnd",
                'available' => $available,
                'price' => $field->price
            ];
        }
        
        return $slots;
    }/**
     * API endpoint for field search (AJAX)
     */
    public function apiSearch(Request $request)
    {
        $query = Field::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', "%{$request->location}%");
        }        // Handle sorting
        if ($request->filled('sort_by')) {
            $sortField = $request->sort_by;
            $sortDirection = $request->get('sort_dir', 'asc');
            
            if (in_array($sortField, ['name', 'price', 'created_at'])) {
                $query->orderBy($sortField, $sortDirection);
            }
        } elseif ($request->filled('sort')) {
            // Handle simplified sort parameters for compatibility
            $sort = $request->sort;
            switch ($sort) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name':
                    $query->orderBy('name', 'asc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            // Default sorting
            $query->orderBy('created_at', 'desc');
        }$perPage = $request->get('per_page', 10);
        $perPage = min($perPage, 50); // Maximum 50 per page
        
        $fields = $query->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->paginate($perPage, ['id', 'name', 'location', 'price', 'category', 'image_url']);

        // Add rating field to each field
        $fields->getCollection()->transform(function ($field) {
            $field->rating = round($field->reviews_avg_rating ?? 0, 1);
            return $field;
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'fields' => $fields
            ]
        ]);
    }

    /**
     * API endpoint to get field availability on a specific date
     */    public function getAvailability(Request $request, $fieldId)
    {
        try {
            // Find field from database
            $field = Field::find($fieldId);
            if (!$field) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Lapangan tidak ditemukan'
                ], 404);
            }

            $date = $request->get('date', date('Y-m-d'));
            
            // Validate date
            if (strtotime($date) < strtotime(date('Y-m-d'))) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => ['date' => ['Tanggal tidak valid. Pilih tanggal hari ini atau ke depan.']]
                ], 422);
            }

            // Get all possible time slots for this field
            $allSlots = $this->getAvailableTimeSlots($field, $date);
            
            // Get booked slots from database (simulated for now)
            $bookedSlots = $this->getBookedSlots($fieldId, $date);
              // Create available time slots with pricing
            $availableSlots = [];
            foreach ($allSlots as $timeSlot) {
                // Format berbeda antara getAvailableTimeSlots untuk halaman show dan getAvailability
                $time = isset($timeSlot['time']) ? $timeSlot['time'] : $timeSlot;
                $available = isset($timeSlot['available']) ? $timeSlot['available'] : !in_array($time, $bookedSlots);
                
                $availableSlots[] = [
                    'time' => $time,
                    'available' => $available,
                    'price' => $field->price
                ];
            }
            
            $totalAvailable = count(array_filter($availableSlots, function($slot) {
                return $slot['available'];
            }));
            
            return response()->json([
                'status' => 'success',
                'data' => [
                    'field_id' => (int)$fieldId,
                    'date' => $date,
                    'available_slots' => $availableSlots,
                    'total_available' => $totalAvailable,
                    'total_slots' => count($availableSlots)
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan dalam mengambil data ketersediaan'
            ], 500);
        }
    }    /**
     * Get booked time slots for a field on a specific date
     */
    private function getBookedSlots($fieldId, $date)
    {
        // Query actual bookings from database
        $bookedSlots = [];
        
        try {
            // Get all confirmed bookings for this field on the specified date
            $bookings = \App\Models\Booking::where('field_id', $fieldId)
                ->where('date', $date)
                ->where('status', '!=', 'cancelled')
                ->where('payment_status', '!=', 'failed')
                ->select('start_time', 'end_time')
                ->get();
            
            foreach ($bookings as $booking) {
                // Convert time range to individual hour slots
                $startHour = intval(substr($booking->start_time, 0, 2));
                $endHour = intval(substr($booking->end_time, 0, 2));
                
                for ($hour = $startHour; $hour < $endHour; $hour++) {
                    $timeSlot = ($hour < 10 ? '0' : '') . $hour . ':00';
                    $bookedSlots[] = $timeSlot;
                }
            }
            
        } catch (\Exception $e) {
            // If database query fails, return empty array
            // In production, you might want to log this error
            \Log::error('Failed to get booked slots: ' . $e->getMessage());
        }
        
        return array_unique($bookedSlots);
    }
}
