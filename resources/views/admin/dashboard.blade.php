@extends('layouts.app')
@section('title', 'Dashboard Admin – LapangKuy')

@section('content')
<div class="container-fluid">
    <!-- Quick Actions & Management -->
    <div class="card mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Quick Actions & Management</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-success w-100 h-100 d-flex flex-column justify-content-center align-items-center">
                        <i class="fas fa-users fa-2x mb-2"></i>
                        <div class="fw-bold text-success">Kelola Users</div>
                        <div class="text-muted">{{ $userCount ?? '0' }} users</div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('admin.fields') }}" class="btn btn-outline-success w-100 h-100 d-flex flex-column justify-content-center align-items-center">
                        <i class="fas fa-futbol fa-2x mb-2"></i>
                        <div class="fw-bold text-success">Kelola Lapangan</div>
                        <div class="text-muted">{{ $fieldCount ?? '0' }} fields</div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('admin.bookings') }}" class="btn btn-outline-warning w-100 h-100 d-flex flex-column justify-content-center align-items-center position-relative">
                        <i class="fas fa-calendar-check fa-2x mb-2 text-warning"></i>
                        <div class="fw-bold text-warning">Kelola Booking</div>
                        <div class="text-muted">{{ $bookingCount ?? '0' }} bookings</div>
                        @if(isset($pendingBookingCount) && $pendingBookingCount > 0)
                            <span class="position-absolute top-0 end-0 translate-middle badge rounded-pill bg-warning text-dark" style="font-size:0.9rem;">{{ $pendingBookingCount }}</span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Dashboard Admin</h1>
            <p class="text-muted">Kelola seluruh sistem LapangKuy</p>

        </div>
    </div>

    <!-- Real-time Stats Bar -->
    <div class="row g-2 mb-4">
        <div class="col-md-3">
            <div class="alert alert-info mb-0 d-flex align-items-center">
                <i class="fas fa-calendar-day me-2"></i>
                <div>
                    <strong id="todayBookings">{{ $stats['today_bookings'] ?? 0 }}</strong>
                    <small class="d-block">Booking Hari Ini</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="alert alert-warning mb-0 d-flex align-items-center">
                <i class="fas fa-clock me-2"></i>
                <div>
                    <strong id="pendingBookings">{{ $stats['pending_bookings'] ?? 0 }}</strong>
                    <small class="d-block">Menunggu Konfirmasi</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="alert alert-success mb-0 d-flex align-items-center">
                <i class="fas fa-users me-2"></i>
                <div>
                    <strong id="activeUsers">{{ $stats['active_users'] ?? 0 }}</strong>
                    <small class="d-block">User Aktif (30 hari)</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="alert alert-danger mb-0 d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <div>
                    <strong id="unpaidBookings">{{ $stats['unpaid_bookings'] ?? 0 }}</strong>
                    <small class="d-block">Pembayaran Tertunggak</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-users fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="row">
                                <div class="col">
                                    <p class="text-muted mb-1 small">Total Users</p>
                                    <h4 class="mb-0">{{ $stats['total_users'] ?? 0 }}</h4>
                                    <div class="d-flex align-items-center mt-1">
                                        <small class="text-success">
                                            <i class="fas fa-arrow-up"></i> +{{ $stats['new_users_this_month'] ?? 0 }}
                                        </small>
                                        <small class="text-muted ms-1">bulan ini</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-futbol fa-2x text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="row">
                                <div class="col">
                                    <p class="text-muted mb-1 small">Total Lapangan</p>
                                    <h4 class="mb-0">{{ $stats['total_fields'] ?? 0 }}</h4>
                                    <div class="d-flex align-items-center mt-1">
                                        <small class="text-success">
                                            <i class="fas fa-arrow-up"></i> +{{ $stats['new_fields_this_month'] ?? 0 }}
                                        </small>
                                        <small class="text-muted ms-1">bulan ini</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-calendar-check fa-2x text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="row">
                                <div class="col">
                                    <p class="text-muted mb-1 small">Total Booking</p>
                                    <h4 class="mb-0">{{ $stats['total_bookings'] ?? 0 }}</h4>
                                    <div class="d-flex align-items-center mt-1">
                                        <small class="text-success">
                                            <i class="fas fa-arrow-up"></i> +{{ $stats['new_bookings_this_month'] ?? 0 }}
                                        </small>
                                        <small class="text-muted ms-1">bulan ini</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-dollar-sign fa-2x text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="row">
                                <div class="col">
                                    <p class="text-muted mb-1 small">Total Revenue</p>
                                    <h4 class="mb-0">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</h4>
                                    <div class="d-flex align-items-center mt-1">
                                        <small class="text-success">
                                            <i class="fas fa-arrow-up"></i> +{{ number_format($stats['revenue_growth'] ?? 0, 1) }}%
                                        </small>
                                        <small class="text-muted ms-1">vs bulan lalu</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Selected Charts Section -->
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Lapangan Terpopuler</h5>
                </div>
                <div class="card-body">
                    @if(isset($popularFields) && count($popularFields) > 0)
                        @foreach($popularFields as $field)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="avatar-circle bg-success text-white">
                                    {{ $loop->iteration }}
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $field->name }}</h6>
                                <small class="text-muted">{{ $field->location }}</small>
                            </div>
                            <div class="flex-shrink-0 text-end">
                                <span class="badge bg-primary">{{ $field->bookings_count }} bookings</span>
                                <div class="text-success fw-bold">Rp {{ number_format($field->price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Data lapangan belum tersedia</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Peak Hours</h5>
                </div>
                <div class="card-body">
                    @if(isset($peakHours) && count($peakHours) > 0)
                        @foreach($peakHours as $index => $hour)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-primary">{{ sprintf('%02d:00', $hour->hour) }}</span>
                            <div class="flex-grow-1 mx-2">
                                <div class="progress" style="height: 4px;">
                                    <div class="progress-bar" style="width: {{ ($hour->count / $peakHours->first()->count) * 100 }}%"></div>
                                </div>
                            </div>
                            <small class="text-muted">{{ $hour->count }} bookings</small>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">Data jam sibuk belum tersedia</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Management Sections -->
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">User Baru</h5>
                        <a href="{{ route('admin.users') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye"></i> Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($recent_users) && count($recent_users) > 0)
                        @foreach($recent_users as $user)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="avatar-circle bg-primary text-white">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $user->name }}</h6>
                                <small class="text-muted">{{ $user->email }}</small>
                                <div class="mt-1">
                                    <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'owner' ? 'warning' : 'primary') }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada user baru</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Booking Terbaru</h5>
                        <a href="{{ route('admin.bookings') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye"></i> Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($recent_bookings) && count($recent_bookings) > 0)
                        @foreach($recent_bookings as $booking)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="avatar-circle bg-success text-white">
                                    <i class="fas fa-calendar"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $booking->field->name ?? 'Field' }}</h6>
                                <small class="text-muted">{{ $booking->user->name ?? 'User' }}</small>
                                <div class="mt-1">
                                    <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-shrink-0 text-end">
                                <div class="fw-bold text-success">Rp {{ number_format($booking->total_price ?? 0, 0, ',', '.') }}</div>
                                <small class="text-muted">{{ $booking->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada booking terbaru</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- System Settings Modal -->
    <div class="modal fade" id="systemSettingsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-cog me-2"></i>System Settings
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="systemSettingsForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6><i class="fas fa-sync-alt me-2"></i>Dashboard Settings</h6>
                                <div class="mb-3">
                                    <label class="form-label">Auto Refresh</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="autoRefresh" name="auto_refresh" value="1">
                                        <label class="form-check-label" for="autoRefresh">Enable auto refresh</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Refresh Interval (seconds)</label>
                                    <input type="number" class="form-control" id="refreshInterval" name="refresh_interval" min="5" max="300" value="30">
                                    <small class="text-muted">Between 5-300 seconds</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Alert Threshold</label>
                                    <input type="number" class="form-control" id="alertThreshold" name="alert_threshold" min="1" max="100" value="10">
                                    <small class="text-muted">Number of items before showing alert</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6><i class="fas fa-bell me-2"></i>Notification Settings</h6>
                                <div class="mb-3">
                                    <label class="form-label">Email Notifications</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="emailNotifs" name="email_notifications" value="1" checked>
                                        <label class="form-check-label" for="emailNotifs">Enable email notifications</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">SMS Notifications</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="smsNotifs" name="sms_notifications" value="1">
                                        <label class="form-check-label" for="smsNotifs">Enable SMS notifications</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Maintenance Mode</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="maintenanceMode" name="maintenance_mode" value="1">
                                        <label class="form-check-label" for="maintenanceMode">Enable maintenance mode</label>
                                    </div>
                                    <small class="text-muted">Will show maintenance page to users</small>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <h6><i class="fas fa-tools me-2"></i>System Maintenance</h6>
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-outline-warning" onclick="clearCache()">
                                        <i class="fas fa-broom me-2"></i>Clear System Cache
                                    </button>
                                    <button type="button" class="btn btn-outline-info" onclick="createBackup()">
                                        <i class="fas fa-download me-2"></i>Create Backup
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6><i class="fas fa-chart-line me-2"></i>Performance</h6>
                                <div class="mb-2">
                                    <small class="text-muted">Cache Status</small>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-success" style="width: 85%"></div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">Database Performance</small>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-info" style="width: 92%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="saveSystemSettings()">
                            <i class="fas fa-save me-2"></i>Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Tombol/box Generate Report, System Settings, dan Audit Logs dihapus --}}

</div>
@endsection

@section('scripts')
<script>
    // Existing script content...

    // Remove or comment out any script related to the removed features
</script>
@endsection
