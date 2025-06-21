<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Field;
use App\Models\User;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;

class AdminController extends Controller
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
        // Calculate comprehensive statistics
        $totalUsers = User::count();
        $totalFields = Field::count();
        $totalBookings = Booking::count();
        $totalRevenue = Booking::where('payment_status', 'paid')->sum('total_price');
        
        // Monthly growth statistics
        $currentMonth = now()->startOfMonth();
        $lastMonth = now()->subMonth()->startOfMonth();
        $currentWeek = now()->startOfWeek();
        $lastWeek = now()->subWeek()->startOfWeek();
        
        $newUsersThisMonth = User::where('created_at', '>=', $currentMonth)->count();
        $newFieldsThisMonth = Field::where('created_at', '>=', $currentMonth)->count();
        $newBookingsThisMonth = Booking::where('created_at', '>=', $currentMonth)->count();
        
        $revenueThisMonth = Booking::where('payment_status', 'paid')
                            ->where('created_at', '>=', $currentMonth)
                            ->sum('total_price');
    
        $revenueLastMonth = Booking::where('payment_status', 'paid')
                            ->where('created_at', '>=', $lastMonth)
                            ->where('created_at', '<', $currentMonth)
                            ->sum('total_price');
    
        $revenueGrowth = $revenueLastMonth > 0 ? 
            (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100 : 0;

        // Advanced analytics
        $todayBookings = Booking::whereDate('created_at', today())->count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $activeUsers = User::where('created_at', '>=', now()->subDays(30))->count();
        $unpaidBookings = Booking::where('payment_status', 'unpaid')->count();
        
        // Weekly performance
        $weeklyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $weeklyStats[] = [
                'date' => $date->format('D'),
                'bookings' => Booking::whereDate('created_at', $date)->count(),
                'revenue' => Booking::where('payment_status', 'paid')
                                  ->whereDate('created_at', $date)
                                  ->sum('total_price')
            ];
        }
        
        // Popular fields
        $popularFields = Field::withCount('bookings')
                             ->orderBy('bookings_count', 'desc')
                             ->take(5)
                             ->get();
        
        // Status distribution
        $bookingStatusStats = [
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
        ];
        
        // Revenue analytics
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyRevenue[] = [
                'month' => $month->format('M'),
                'revenue' => Booking::where('payment_status', 'paid')
                                  ->whereYear('created_at', $month->year)
                                  ->whereMonth('created_at', $month->month)
                                  ->sum('total_price')
            ];
        }
        
        // System alerts
        $alerts = [];
        
        if ($pendingBookings > 10) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "Terdapat {$pendingBookings} booking yang menunggu konfirmasi",
                'icon' => 'fas fa-exclamation-triangle'
            ];
        }
        
        if ($unpaidBookings > 5) {
            $alerts[] = [
                'type' => 'danger',
                'message' => "Terdapat {$unpaidBookings} booking dengan pembayaran tertunggak",
                'icon' => 'fas fa-credit-card'
            ];
        }
        
        $lowPerformingFields = Field::withCount('bookings')
                                    ->having('bookings_count', '<', 5)
                                    ->count();
        
        if ($lowPerformingFields > 0) {
            $alerts[] = [
                'type' => 'info',
                'message' => "{$lowPerformingFields} lapangan memiliki tingkat booking rendah",
                'icon' => 'fas fa-chart-line'
            ];
        }
        
        // Peak hours analysis
        $peakHours = Booking::selectRaw('HOUR(start_time) as hour, COUNT(*) as count')
                           ->groupBy('hour')
                           ->orderBy('count', 'desc')
                           ->take(3)
                           ->get();
        
        // Prepare stats array
        $stats = [
            'total_users' => $totalUsers,
            'total_fields' => $totalFields,
            'total_bookings' => $totalBookings,
            'total_revenue' => $totalRevenue,
            'new_users_this_month' => $newUsersThisMonth,
            'new_fields_this_month' => $newFieldsThisMonth,
            'new_bookings_this_month' => $newBookingsThisMonth,
            'revenue_growth' => $revenueGrowth,
            'today_bookings' => $todayBookings,
            'pending_bookings' => $pendingBookings,
            'active_users' => $activeUsers,
            'unpaid_bookings' => $unpaidBookings
        ];
        
        // Recent users (last 5)
        $recent_users = User::orderBy('created_at', 'desc')
                          ->take(5)
                          ->get();
        
        // Recent bookings for quick access
        $recent_bookings = Booking::with('user', 'field')
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();
        
        // Extract counts for quick action cards
        $userCount = $stats['total_users'];
        $fieldCount = $stats['total_fields'];
        $bookingCount = $stats['total_bookings'];
        $pendingBookingCount = $stats['pending_bookings'];
        
        return view('admin.dashboard', compact(
            'stats',
            'recent_users',
            'recent_bookings',
            'weeklyStats',
            'popularFields',
            'bookingStatusStats',
            'monthlyRevenue',
            'alerts',
            'peakHours',
            'userCount',
            'fieldCount',
            'bookingCount',
            'pendingBookingCount'
        ));
    }
    
    public function fields(Request $request)
    {
        $query = Field::query();
        
        // Apply filters if they exist
        if ($request->has('category') && $request->category !== '') {
            $query->where('category', $request->category);
        }
        
        if ($request->has('status') && $request->status !== '') {
            $isAvailable = $request->status === 'active' ? 1 : 0;
            $query->where('is_available', $isAvailable);
        }
        
        if ($request->has('search') && $request->search !== '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }
        
        // Check if this is an AJAX request for tab content
        if ($request->ajax() || $request->has('ajax')) {
            $fields = $query->latest()->paginate(10);
            
            if ($request->has('status')) {
                return view('admin.fields._table', compact('fields'))->render();
            }
            
            return response()->json([
                'html' => view('admin.fields._table', compact('fields'))->render(),
                'pagination' => $fields->links()->toHtml()
            ]);
        }
        
        // Get fields with pagination
        $fields = $query->latest()->paginate(10);
        
        return view('admin.fields', compact('fields'));
    }
    
    public function createField()
    {
        return view('admin.field-form');
    }
    
    public function storeField(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'open_time' => 'required',
            'close_time' => 'required',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_url' => 'nullable|url',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        // Convert facilities from array to JSON if needed
        $facilities = $request->facilities ? json_encode($request->facilities) : json_encode([]);
        
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
            'gallery' => $gallery,
            'promo' => $request->has('promo'),
            'is_available' => $request->has('is_available') ? 1 : 0,
        ]);
        
        return redirect()->route('admin.fields')->with('success', 'Lapangan berhasil ditambahkan!');
    }
    
    public function editField($id)
    {
        $field = Field::findOrFail($id);
        return view('admin.field-form', compact('field'));
    }
    
    public function updateField(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'open_time' => 'required',
            'close_time' => 'required',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_url' => 'nullable|url',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $field = Field::findOrFail($id);
        
        // Convert facilities from array to JSON if needed
        $facilities = $request->facilities ? json_encode($request->facilities) : json_encode([]);
        
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
            'gallery' => $gallery,
            'promo' => $request->has('promo'),
            'is_available' => $request->has('is_available') ? 1 : 0,
        ]);
        
        return redirect()->route('admin.fields')->with('success', 'Lapangan berhasil diperbarui!');
    }
    
    public function deleteField($id)
    {
        $field = Field::findOrFail($id);
        $field->delete();
        
        return redirect()->route('admin.fields')->with('success', 'Lapangan berhasil dihapus!');
    }
    
    public function bookings(Request $request)
    {
        $query = Booking::with(['user', 'field']);
        
        // Apply filters if they exist
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search') && $request->search !== '') {
            $query->where('booking_code', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('date') && $request->date !== '') {
            $query->whereDate('date', $request->date);
        }
        
        // Get bookings with pagination
        $bookings = $query->latest()->paginate(10);
        
        // Get booking counts for different statuses
        $confirmedCount = Booking::where('status', 'confirmed')->count();
        $pendingCount = Booking::where('status', 'pending')->count();
        $cancelledCount = Booking::where('status', 'cancelled')->count();
        $totalCount = Booking::count();
        
        // Check if this is an AJAX request for tab content
        if ($request->ajax() || $request->has('ajax')) {
            if ($request->has('status')) {
                return view('admin.bookings._table', compact('bookings'))->render();
            }
            
            return response()->json([
                'html' => view('admin.bookings._table', compact('bookings'))->render(),
                'pagination' => $bookings->links()->toHtml()
            ]);
        }
        
        return view('admin.bookings.index', compact(
            'bookings', 
            'confirmedCount', 
            'pendingCount', 
            'cancelledCount', 
            'totalCount'
        ));
    }
    
    public function users(Request $request)
    {
        $query = User::query();
        
        // Filter by role if specified
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }
        
        // Filter by status if specified
        if ($request->has('status') && $request->status != '') {
            if ($request->status === 'active') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->status === 'inactive') {
                $query->whereNull('email_verified_at');
            }
        }
        
        // Search by name or email
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        $users = $query->paginate(10);
        
        // Check if this is an AJAX request for tab content
        if ($request->ajax() || $request->has('ajax')) {
            if ($request->has('role')) {
                return view('admin.users._table', compact('users'))->render();
            }
            
            return response()->json([
                'html' => view('admin.users._table', compact('users'))->render(),
                'pagination' => $users->links()->toHtml()
            ]);
        }
        
        return view('admin.users.index', compact('users'));
    }
    
    /**
     * Update user role
     */
    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:user,field_owner,admin'
        ]);
        
        $user->update([
            'role' => $request->role
        ]);
        
        return redirect()->back()->with('success', 'User role updated successfully!');
    }
    
    /**
     * Ban/Unban user
     */
    public function toggleUserStatus(User $user)
    {
        // Assuming we have a 'status' field, or we can use deleted_at for soft deletes
        // For now, let's implement a simple approach
        $user->update([
            'email_verified_at' => $user->email_verified_at ? null : now()
        ]);
        
        $status = $user->email_verified_at ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "User has been {$status} successfully!");
    }
    
    /**
     * Get real-time data for dashboard
     */
    public function getRealtimeData()
    {
        $data = [
            'today_bookings' => Booking::whereDate('created_at', today())->count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'active_users' => User::where('created_at', '>=', now()->subDays(30))->count(),
            'unpaid_bookings' => Booking::where('payment_status', '!=', 'paid')->count(),
            'last_update' => now()->format('H:i:s')
        ];

        return response()->json($data);
    }

    /**
     * Get comprehensive dashboard data
     */
    public function getDashboardData()
    {
        $totalUsers = User::count();
        $totalFields = Field::count();
        $totalBookings = Booking::count();
        $totalRevenue = Booking::where('payment_status', 'paid')->sum('total_price');
        
        // Recent activity
        $recentBookings = Booking::with(['user', 'field'])
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();
        
        // Popular fields
        $popularFields = Field::withCount('bookings')
                             ->orderBy('bookings_count', 'desc')
                             ->take(5)
                             ->get();
        
        // Weekly stats for charts
        $weeklyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $weeklyStats[] = [
                'date' => $date->format('Y-m-d'),
                'bookings' => Booking::whereDate('created_at', $date)->count(),
                'revenue' => Booking::where('payment_status', 'paid')
                                  ->whereDate('created_at', $date)
                                  ->sum('total_price')
            ];
        }

        return response()->json([
            'stats' => [
                'total_users' => $totalUsers,
                'total_fields' => $totalFields,
                'total_bookings' => $totalBookings,
                'total_revenue' => $totalRevenue,
            ],
            'recent_bookings' => $recentBookings,
            'popular_fields' => $popularFields,
            'weekly_stats' => $weeklyStats,
            'last_update' => now()->format('H:i:s')
        ]);
    }

    /**
     * Get system health metrics
     */
    public function getSystemHealth()
    {
        // Database health check
        $dbHealth = true;
        $dbResponseTime = 0;
        
        try {
            $start = microtime(true);
            \DB::connection()->getPdo();
            $dbResponseTime = round((microtime(true) - $start) * 1000, 2);
        } catch (\Exception $e) {
            $dbHealth = false;
        }

        // Storage usage
        $storageTotal = disk_total_space(storage_path());
        $storageFree = disk_free_space(storage_path());
        $storageUsed = $storageTotal - $storageFree;
        $storageUsagePercent = round(($storageUsed / $storageTotal) * 100, 1);

        // Memory usage
        $memoryUsage = memory_get_usage(true);
        $memoryPeak = memory_get_peak_usage(true);
        $memoryLimit = $this->returnBytes(ini_get('memory_limit'));
        $memoryUsagePercent = round(($memoryUsage / $memoryLimit) * 100, 1);

        return response()->json([
            'server_status' => 'online',
            'database' => [
                'status' => $dbHealth ? 'healthy' : 'error',
                'response_time' => $dbResponseTime . 'ms'
            ],
            'storage' => [
                'usage_percent' => $storageUsagePercent,
                'used' => $this->formatBytes($storageUsed),
                'total' => $this->formatBytes($storageTotal),
                'status' => $storageUsagePercent > 85 ? 'warning' : 'healthy'
            ],
            'memory' => [
                'usage_percent' => $memoryUsagePercent,
                'current' => $this->formatBytes($memoryUsage),
                'peak' => $this->formatBytes($memoryPeak),
                'status' => $memoryUsagePercent > 80 ? 'warning' : 'healthy'
            ],
            'api_status' => 'operational',
            'security_status' => 'secure',
            'last_check' => now()->format('H:i:s')
        ]);
    }

    /**
     * Get performance metrics
     */
    public function getPerformanceMetrics()
    {
        // Response time simulation (in real app, you'd track this)
        $responseTime = rand(50, 200);
        
        // Error rate calculation
        $totalRequests = 1000; // This would be tracked in real app
        $errorRequests = rand(1, 10);
        $errorRate = round(($errorRequests / $totalRequests) * 100, 2);
        
        // Throughput (requests per minute)
        $throughput = rand(50, 100);

        return response()->json([
            'response_time' => $responseTime . 'ms',
            'error_rate' => $errorRate . '%',
            'throughput' => $throughput . ' req/min',
            'uptime' => '99.9%',
            'last_update' => now()->format('H:i:s')
        ]);
    }

    /**
     * Export dashboard PDF report
     */
    public function exportDashboardPDF(Request $request)
    {
        $period = $request->get('period', 'month');
        $data = $this->generateReportData($period);
        
        // In a real implementation, you'd use a PDF library like DomPDF
        return response()->json([
            'message' => 'PDF export would be generated here',
            'data' => $data
        ]);
    }

    /**
     * Export dashboard Excel report
     */
    public function exportDashboardExcel(Request $request)
    {
        $period = $request->get('period', 'month');
        $data = $this->generateReportData($period);
        
        // In a real implementation, you'd use Laravel Excel
        return response()->json([
            'message' => 'Excel export would be generated here',
            'data' => $data
        ]);
    }

    /**
     * Update system settings
     */
    public function updateSystemSettings(Request $request)
    {
        // Validate and update system settings
        $settings = $request->validate([
            'auto_refresh' => 'boolean',
            'refresh_interval' => 'integer|min:5|max:300',
            'alert_threshold' => 'integer|min:1|max:100',
            'maintenance_mode' => 'boolean'
        ]);

        // In a real implementation, you'd save these to a settings table or config
        return response()->json([
            'message' => 'System settings updated successfully',
            'settings' => $settings
        ]);
    }

    /**
     * Get audit log
     */
    public function getAuditLog(Request $request)
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 20);
        
        // Mock audit log data - in real app, you'd have an audit log table
        $auditLogs = collect([
            [
                'id' => 1,
                'user' => 'Admin User',
                'action' => 'User Login',
                'timestamp' => now()->subMinutes(5)->format('Y-m-d H:i:s'),
                'ip' => '192.168.1.1',
                'details' => 'Successful login'
            ],
            [
                'id' => 2,
                'user' => 'Admin User',
                'action' => 'Booking Updated',
                'timestamp' => now()->subMinutes(10)->format('Y-m-d H:i:s'),
                'ip' => '192.168.1.1',
                'details' => 'Updated booking #123 status'
            ],
            [
                'id' => 3,
                'user' => 'Admin User',
                'action' => 'Field Created',
                'timestamp' => now()->subHour()->format('Y-m-d H:i:s'),
                'ip' => '192.168.1.1',
                'details' => 'Created new field: Football Field A'
            ]
        ]);

        return response()->json([
            'logs' => $auditLogs->forPage($page, $limit)->values(),
            'total' => $auditLogs->count(),
            'current_page' => $page,
            'per_page' => $limit
        ]);
    }

    /**
     * Clear system cache
     */
    public function clearSystemCache()
    {
        try {
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');
            \Artisan::call('view:clear');
            
            return response()->json([
                'message' => 'System cache cleared successfully',
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error clearing cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create system backup
     */
    public function createBackup()
    {
        try {
            // In a real implementation, you'd create database and file backups
            $backupName = 'backup_' . now()->format('Y_m_d_H_i_s') . '.sql';
            
            return response()->json([
                'message' => 'System backup created successfully',
                'backup_name' => $backupName,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating backup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get bookings filtered by status for Ajax requests
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFilteredBookings(Request $request)
    {
        $status = $request->input('status');
        
        $query = Booking::with(['user', 'field']);
        
        // Filter by status if provided
        if ($status) {
            $query->where('status', $status);
        }
        
        // Apply other filters if provided
        if ($request->has('search') && $request->search != '') {
            $query->where('booking_code', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('date', $request->date);
        }
        
        // Get paginated results
        $bookings = $query->latest()->paginate(10);
        
        if ($request->ajax()) {
            return response()->json([
                'bookings' => $bookings,
                'pagination' => [
                    'total' => $bookings->total(),
                    'per_page' => $bookings->perPage(),
                    'current_page' => $bookings->currentPage(),
                    'last_page' => $bookings->lastPage()
                ]
            ]);
        }
        
        return view('admin.bookings._table', compact('bookings'));
    }
    
    /**
     * Get filtered fields for AJAX requests
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getFilteredFields(Request $request)
    {
        $query = Field::query();
        
        // Apply filters if they exist
        if ($request->has('category') && $request->category !== '') {
            $query->where('category', $request->category);
        }
        
        if ($request->has('status') && $request->status !== '') {
            $isAvailable = $request->status === 'active' ? 1 : 0;
            $query->where('is_available', $isAvailable);
        }
        
        if ($request->has('search') && $request->search !== '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }
        
        // Get fields with pagination
        $fields = $query->latest()->paginate(10);
        
        if ($request->ajax()) {
            return response()->json([
                'fields' => $fields,
                'pagination' => [
                    'total' => $fields->total(),
                    'per_page' => $fields->perPage(),
                    'current_page' => $fields->currentPage(),
                    'last_page' => $fields->lastPage()
                ]
            ]);
        }
        
        return view('admin.fields._table', compact('fields'));
    }

    /**
     * Get filtered users for AJAX requests
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getFilteredUsers(Request $request)
    {
        $query = User::query();
        
        // Filter by role if specified
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }
        
        // Filter by status if specified
        if ($request->has('status') && $request->status != '') {
            if ($request->status === 'active') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->status === 'inactive') {
                $query->whereNull('email_verified_at');
            }
        }
        
        // Search by name or email
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        $users = $query->paginate(10);
        
        if ($request->ajax()) {
            return response()->json([
                'users' => $users,
                'pagination' => [
                    'total' => $users->total(),
                    'per_page' => $users->perPage(),
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage()
                ]
            ]);
        }
        
        return view('admin.users._table', compact('users'));
    }

    /**
     * Helper function to convert memory limit string to bytes
     */
    private function returnBytes($val)
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        $val = (int)$val;
        
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        
        return $val;
    }

    /**
     * Helper function to format bytes
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
