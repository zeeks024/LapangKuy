@extends('layouts.app')

@section('title', 'Dashboard Pemilik Lapangan')

@section('content')
<div class="container py-4">
    <!-- Header with Gradient Background -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-gradient shadow-sm" style="background-color: #043E03;">
                <div class="card-body py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-white">
                            <h2 class="mb-1">Dashboard Pemilik Lapangan</h2>
                            <p class="mb-0 opacity-75">Selamat datang kembali, {{ auth()->user()->name ?? 'Owner' }}!</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('owner.fields.create') }}" class="btn btn-light">
                                <i class="fas fa-plus-circle me-1"></i>Tambah Lapangan
                            </a>
                            <button class="btn btn-outline-light" onclick="refreshDashboard()">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- Statistics Cards -->
    <div class="row g-3 mb-3">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-gradient rounded-circle p-3 text-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-futbol text-white fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Lapangan</h6>
                            <div class="d-flex align-items-center">
                                <h3 class="mb-0">{{ $totalFields }}</h3>
                                @if($totalFields > 0)
                                    <span class="badge bg-soft-success text-success ms-2">
                                        <i class="fas fa-check me-1"></i>Aktif
                                    </span>
                                @endif
                            </div>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-gradient rounded-circle p-3 text-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-calendar-check text-white fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Booking</h6>
                            <div class="d-flex align-items-center">
                                <h3 class="mb-0">{{ $totalBookings }}</h3>
                                @php
                                    $lastWeekBookings = array_sum(array_column($bookingStats, 'count'));
                                @endphp
                                @if($lastWeekBookings > 0)
                                    <span class="badge bg-soft-success text-success ms-2">
                                        <i class="fas fa-arrow-up me-1"></i>{{ $lastWeekBookings }} minggu ini
                                    </span>
                                @endif
                            </div>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ min($totalBookings * 5, 100) }}%;" aria-valuenow="{{ min($totalBookings * 5, 100) }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-gradient rounded-circle p-3 text-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-money-bill-wave text-white fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Pendapatan</h6>
                            <div class="d-flex align-items-center">
                                <h3 class="mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                                @if($totalBookings > 0)
                                    <span class="badge bg-soft-info text-info ms-2">
                                        <i class="fas fa-chart-line me-1"></i>{{ number_format($totalRevenue / $totalBookings, 0, ',', '.') }}/booking
                                    </span>
                                @endif
                            </div>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-info" role="progressbar" style="width: {{ min($totalRevenue / 10000, 100) }}%;" aria-valuenow="{{ min($totalRevenue / 10000, 100) }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-gradient rounded-circle p-3 text-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-star text-white fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Rating Rata-rata</h6>
                            <div class="d-flex align-items-center">
                                <h3 class="mb-0">{{ $avgRating }}</h3>
                                <span class="badge {{ $avgRating >= 4.0 ? 'bg-soft-success text-success' : ($avgRating >= 3.0 ? 'bg-soft-warning text-warning' : 'bg-soft-danger text-danger') }} ms-2">
                                    {{ $avgRating >= 4.0 ? 'Sangat Baik' : ($avgRating >= 3.0 ? 'Baik' : 'Perlu Perbaikan') }}
                                </span>
                            </div>
                            <div class="text-warning">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= floor($avgRating))
                                        <i class="fas fa-star"></i>
                                    @elseif ($i - 0.5 <= $avgRating)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                                <small class="text-muted ms-1">({{ $totalReviews }} ulasan)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- Charts and Analytics -->
    <div class="row g-3 mb-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Statistik Booking</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                7 Hari Terakhir
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">7 Hari Terakhir</a></li>
                                <li><a class="dropdown-item" href="#">30 Hari Terakhir</a></li>
                                <li><a class="dropdown-item" href="#">3 Bulan Terakhir</a></li>
                            </ul>
                        </div>
                    </div>
                </div>                <div class="card-body py-2">
                    <div class="chart-container" style="height: 180px">
                        <canvas id="bookingChart"></canvas>
                    </div>
                </div>
            </div>        </div>
        
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="mb-0">Kategori Lapangan</h5>
                </div>                <div class="card-body py-2">
                    <div class="chart-container" style="height: 150px">
                        <canvas id="categoryChart"></canvas>
                    </div><div class="mt-2">
                        @php
                            $totalCategories = array_sum(array_column($categoryStats, 'count'));
                            $backgroundColors = ['primary', 'success', 'warning', 'secondary', 'danger', 'info'];
                        @endphp
                          @foreach($categoryStats as $index => $category)
                            <div class="d-flex justify-content-between mb-1">
                                <div class="d-flex align-items-center">
                                    <div class="me-2" style="width: 8px; height: 8px; background-color: var(--bs-{{ $backgroundColors[$index % count($backgroundColors)] }});"></div>
                                    <span class="text-muted small">{{ $category['category'] }}</span>
                                </div>
                                <span class="fw-bold small">{{ round(($category['count'] / $totalCategories) * 100) }}%</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- Quick Actions -->
    <div class="row g-3 mb-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('owner.fields') }}" class="text-decoration-none">
                                <div class="card border border-primary text-center h-100">
                                    <div class="card-body">
                                        <i class="bi bi-list-ul text-primary fs-1 mb-3"></i>
                                        <h6 class="card-title text-primary">Kelola Lapangan</h6>
                                        <p class="card-text text-muted small">Lihat, edit, dan kelola semua lapangan Anda</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('owner.bookings') }}" class="text-decoration-none">
                                <div class="card border border-success text-center h-100">
                                    <div class="card-body">
                                        <i class="bi bi-calendar-event text-success fs-1 mb-3"></i>
                                        <h6 class="card-title text-success">Kelola Booking</h6>
                                        <p class="card-text text-muted small">Lihat dan konfirmasi booking masuk</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('owner.fields.create') }}" class="text-decoration-none">
                                <div class="card border border-info text-center h-100">
                                    <div class="card-body">
                                        <i class="bi bi-plus-circle text-info fs-1 mb-3"></i>
                                        <h6 class="card-title text-info">Tambah Lapangan</h6>
                                        <p class="card-text text-muted small">Daftarkan lapangan baru</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('user.profile') }}" class="text-decoration-none">
                                <div class="card border border-warning text-center h-100">
                                    <div class="card-body">
                                        <i class="bi bi-person-gear text-warning fs-1 mb-3"></i>
                                        <h6 class="card-title text-warning">Pengaturan</h6>
                                        <p class="card-text text-muted small">Kelola profil dan preferensi</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity and Fields -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Booking Terbaru</h5>
                    <a href="{{ route('owner.bookings') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body">                <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Lapangan</th>
                                    <th>Customer</th>
                                    <th>Tanggal</th>
                                    <th>Waktu</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($recentBookings->count() > 0)
                                    @foreach($recentBookings as $booking)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @php
                                                        $colors = ['primary', 'success', 'info', 'warning', 'danger'];
                                                        $color = $colors[$loop->index % count($colors)];
                                                    @endphp
                                                    <div class="avatar-sm bg-{{ $color }} bg-gradient rounded me-2 d-flex align-items-center justify-content-center">
                                                        <i class="bi bi-building text-white"></i>
                                                    </div>
                                                    <span>{{ $booking->field->name }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $booking->user->name }}</td>
                                            <td>{{ date('d M Y', strtotime($booking->date)) }}</td>
                                            <td>{{ date('H:i', strtotime($booking->start_time)) }} - {{ date('H:i', strtotime($booking->end_time)) }}</td>
                                            <td>
                                                @php
                                                    $statusColor = [
                                                        'pending' => 'warning',
                                                        'confirmed' => 'success',
                                                        'cancelled' => 'danger',
                                                        'completed' => 'info'
                                                    ];
                                                    $color = $statusColor[$booking->status] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-{{ $color }}">{{ ucfirst($booking->status) }}</span>
                                            </td>
                                            <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center py-3">Belum ada booking terbaru</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Lapangan Saya</h5>
                    <a href="{{ route('owner.fields') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @if($fields->count() > 0)
                        @foreach($fields->take(3) as $field)
                        <div class="d-flex align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            @if($field->image_url)
                                <img src="{{ $field->image_url }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;" alt="{{ $field->name }}">
                            @else
                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $field->name }}</h6>
                                <p class="text-muted small mb-1">{{ ucfirst($field->category) }} • {{ $field->location }}</p>
                                <span class="text-primary fw-bold">Rp {{ number_format($field->price, 0, ',', '.') }}/jam</span>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('fields.show', $field) }}">
                                        <i class="bi bi-eye me-2"></i>Lihat
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('owner.fields.edit', $field) }}">
                                        <i class="bi bi-pencil me-2"></i>Edit
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-building fs-1 text-muted mb-3"></i>
                            <h6>Belum Ada Lapangan</h6>
                            <p class="text-muted small">Mulai dengan menambahkan lapangan pertama Anda</p>
                            <a href="{{ route('owner.fields.create') }}" class="btn btn-primary btn-sm">Tambah Lapangan</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 2rem;
    height: 2rem;
}

.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
}

.card {
    transition: all 0.3s ease;
}

.progress {
    height: 5px;
    overflow: hidden;
    background-color: #e9ecef;
    border-radius: 0.25rem;
}

.progress-bar {
    transition: width 1s ease;
}

/* Soft background badges */
.bg-soft-primary {
    background-color: rgba(13, 110, 253, 0.1) !important;
}

.bg-soft-success {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

.bg-soft-info {
    background-color: rgba(13, 202, 240, 0.1) !important;
}

.bg-soft-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.bg-soft-danger {
    background-color: rgba(220, 53, 69, 0.1) !important;
}

.bg-soft-secondary {
    background-color: rgba(108, 117, 125, 0.1) !important;
}

/* Chart container styles */
.chart-container {
    position: relative;
    height: 100%;
    width: 100%;
    overflow: hidden;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Booking Chart
const bookingCtx = document.getElementById('bookingChart').getContext('2d');
window.bookingChart = new Chart(bookingCtx, {
    type: 'line',
    data: {
        labels: @json(array_column($bookingStats, 'date')),
        datasets: [{
            label: 'Booking',
            data: @json(array_column($bookingStats, 'count')),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.4,
            fill: true,
            borderWidth: 2,
            pointRadius: 3
        }]
    },    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    title: function(tooltipItems) {
                        return tooltipItems[0].label;
                    },
                    label: function(context) {
                        return context.raw + ' booking';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    display: false
                },
                ticks: {
                    precision: 0,
                    font: {
                        size: 10
                    }
                }
            },
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    font: {
                        size: 10
                    }
                }
            }
        },
        layout: {
            padding: {
                left: 5,
                right: 5,
                top: 5,
                bottom: 5
            }
        }
    }
});

// Category Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
const categoryLabels = @json(array_column($categoryStats, 'category'));
const categoryData = @json(array_column($categoryStats, 'count'));
const backgroundColors = ['#0d6efd', '#198754', '#ffc107', '#6c757d', '#dc3545', '#0dcaf0'];

window.categoryChart = new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: categoryLabels,
        datasets: [{
            data: categoryData,
            backgroundColor: backgroundColors.slice(0, categoryLabels.length),
            borderWidth: 0,
            cutout: '65%'
        }]
    },    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = Math.round((context.raw / total) * 100);
                        return context.label + ': ' + context.raw + ' (' + percentage + '%)';
                    }
                }
            }
        },
        layout: {
            padding: {
                left: 5,
                right: 5,
                top: 5,
                bottom: 0
            }
        }
    }
});

// Reset chart sizes on window resize
window.addEventListener('resize', function() {
    if (window.bookingChart) {
        window.bookingChart.resize();
    }
    if (window.categoryChart) {
        window.categoryChart.resize();
    }
});

// Initialize charts after DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Charts are already initialized in the script above
});

function refreshDashboard() {
    // Create loading overlay
    const overlay = document.createElement('div');
    overlay.style.position = 'fixed';
    overlay.style.top = '0';
    overlay.style.left = '0';
    overlay.style.width = '100%';
    overlay.style.height = '100%';
    overlay.style.backgroundColor = 'rgba(255, 255, 255, 0.7)';
    overlay.style.display = 'flex';
    overlay.style.justifyContent = 'center';
    overlay.style.alignItems = 'center';
    overlay.style.zIndex = '9999';
    
    const spinner = document.createElement('div');
    spinner.className = 'spinner-border text-primary';
    spinner.setAttribute('role', 'status');
    
    const span = document.createElement('span');
    span.className = 'visually-hidden';
    span.innerText = 'Loading...';
    
    spinner.appendChild(span);
    overlay.appendChild(spinner);
    document.body.appendChild(overlay);
    
    // Reload after a short delay to show the animation
    setTimeout(() => {
        location.reload();
    }, 500);
}
</script>
@endsection
