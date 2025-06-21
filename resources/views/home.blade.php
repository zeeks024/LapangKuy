@extends('layouts.app')

@section('title', 'LapangKuy - Sewa Lapangan Olahraga Online')

@section('content')
<!-- Compact Hero Section -->
<section class="hero-compact bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="mb-3">Booking Lapangan Olahraga Mudah</h2>
                <p class="mb-4">Temukan dan booking lapangan futsal, basket, badminton, tennis, dan lainnya dengan cepat.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('fields.index') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-search me-2"></i>Cari Lapangan
                    </a>
                    <a href="#popular-fields" class="btn btn-outline-light">
                        <i class="fas fa-star me-2"></i>Lapangan Populer
                    </a>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <div class="hero-stats">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="stat-card bg-white text-primary rounded p-3">
                                <h5 class="fw-bold mb-1">{{ \App\Models\Field::count() }}+</h5>
                                <small>Lapangan</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-card bg-white text-primary rounded p-3">
                                <h5 class="fw-bold mb-1">{{ \App\Models\Booking::count() }}+</h5>
                                <small>Booking</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Search Section -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="card border-0 shadow-sm">
            <div class="card-body py-3">
                <form action="{{ route('fields.index') }}" method="GET">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <select class="form-select form-select-sm" name="category">
                                <option value="">Pilih Olahraga</option>
                                <option value="Futsal">Futsal</option>
                                <option value="Basket">Basket</option>
                                <option value="Badminton">Badminton</option>
                                <option value="Tenis">Tenis</option>
                                <option value="Voli">Voli</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select form-select-sm" name="location">
                                <option value="">Pilih Lokasi</option>
                                <option value="Jakarta Pusat">Jakarta Pusat</option>
                                <option value="Jakarta Utara">Jakarta Utara</option>
                                <option value="Jakarta Selatan">Jakarta Selatan</option>
                                <option value="Jakarta Timur">Jakarta Timur</option>
                                <option value="Jakarta Barat">Jakarta Barat</option>
                                <option value="Bekasi">Bekasi</option>
                                <option value="Tangerang">Tangerang</option>
                                <option value="Depok">Depok</option>
                                <option value="Semarang">Semarang</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control form-control-sm" name="date" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-search me-1"></i>Cari Lapangan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Popular Fields Section -->
<section class="py-5" id="popular-fields">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Lapangan Terpopuler</h4>
                <p class="text-muted mb-0 small">Lapangan dengan rating terbaik dan paling sering dibooking</p>
            </div>
            <a href="{{ route('fields.index') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-eye me-1"></i>Lihat Semua
            </a>
        </div>
        
        <div class="row g-4">
            @php
                $popularFields = \App\Models\Field::withCount('bookings')
                    ->orderBy('bookings_count', 'desc')
                    ->limit(6)
                    ->get();
            @endphp
            
            @forelse($popularFields as $field)
            <div class="col-lg-4 col-md-6">
                <div class="card field-card-home h-100 border-0 shadow-sm overflow-hidden">
                    <div class="field-card-img-container position-relative">
                        <img src="{{ $field->image_url ?? asset('assets/images/placeholder-field.webp') }}" 
                             class="card-img-top field-card-img" alt="{{ $field->name }}">
                        
                        @if($field->promo)
                        <span class="badge bg-danger position-absolute top-0 start-0 m-2 fs-tiny px-2 py-1">
                            <i class="fas fa-fire me-1"></i>PROMO
                        </span>
                        @endif
                        
                        @php
                            $avgRating = $field->reviews()->avg('rating') ?? 0;
                            $reviewCount = $field->reviews()->count();
                        @endphp
                        @if($reviewCount > 0)
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-warning text-dark fs-tiny px-2 py-1">
                                <i class="fas fa-star"></i> {{ number_format($avgRating, 1) }} ({{ $reviewCount }})
                            </span>
                        </div>
                        @endif
                        <div class="field-overlay-bottom p-2">
                            <h6 class="card-title mb-0 fw-bold text-white text-shadow-sm">{{ Str::limit($field->name, 30) }}</h6>
                        </div>
                    </div>
                    
                    <div class="card-body p-3">
                        <p class="card-text text-muted small mb-2">
                            <i class="fas fa-map-marker-alt text-primary me-1"></i>{{ Str::limit($field->location, 25) }}
                            <span class="mx-1">•</span>
                            <i class="fas fa-tag text-primary me-1"></i>{{ $field->category }}
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="price-info">
                                <h5 class="text-primary fw-bolder mb-0">
                                    Rp{{ number_format($field->price,0,',','.') }}
                                    <small class="text-muted fw-normal">/jam</small>
                                </h5>
                            </div>
                            <a href="{{ route('fields.show', $field->id) }}" class="btn btn-primary btn-sm fw-semibold">
                                <i class="fas fa-calendar-alt me-1"></i> Booking
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>Belum ada lapangan populer saat ini.
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-4">
            <h4 class="mb-2">Mengapa Pilih LapangKuy?</h4>
            <p class="text-muted">Platform booking lapangan terpercaya dengan layanan terbaik</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-3 col-md-6 text-center">
                <div class="feature-card h-100">
                    <div class="feature-icon bg-primary text-white rounded-circle mb-3">
                        <i class="fas fa-search fa-2x"></i>
                    </div>
                    <h6>Pencarian Mudah</h6>
                    <p class="text-muted small">Temukan lapangan sesuai kebutuhan dengan filter yang lengkap</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 text-center">
                <div class="feature-card h-100">
                    <div class="feature-icon bg-success text-white rounded-circle mb-3">
                        <i class="fas fa-calendar-check fa-2x"></i>
                    </div>
                    <h6>Booking Instan</h6>
                    <p class="text-muted small">Booking langsung konfirmasi tanpa perlu menunggu</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 text-center">
                <div class="feature-card h-100">
                    <div class="feature-icon bg-warning text-white rounded-circle mb-3">
                        <i class="fas fa-credit-card fa-2x"></i>
                    </div>
                    <h6>Pembayaran Aman</h6>
                    <p class="text-muted small">Sistem pembayaran online yang aman dan terpercaya</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 text-center">
                <div class="feature-card h-100">
                    <div class="feature-icon bg-info text-white rounded-circle mb-3">
                        <i class="fas fa-headset fa-2x"></i>
                    </div>
                    <h6>Support 24/7</h6>
                    <p class="text-muted small">Layanan customer service yang siap membantu kapan saja</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-4">
            <h4 class="mb-2">Kategori Olahraga</h4>
            <p class="text-muted">Pilih jenis olahraga yang ingin Anda mainkan</p>
        </div>
        
        <div class="row g-3">
            <div class="col-lg-2 col-md-4 col-6">
                <a href="{{ route('fields.index', ['category' => 'Futsal']) }}" class="text-decoration-none">
                    <div class="category-card-simple text-center p-3 border rounded hover-shadow">
                        <i class="fas fa-futbol fa-2x text-primary mb-2"></i>
                        <h6 class="mb-0">Futsal</h6>
                    </div>
                </a>
            </div>
            
            <div class="col-lg-2 col-md-4 col-6">
                <a href="{{ route('fields.index', ['category' => 'Basket']) }}" class="text-decoration-none">
                    <div class="category-card-simple text-center p-3 border rounded hover-shadow">
                        <i class="fas fa-basketball-ball fa-2x text-warning mb-2"></i>
                        <h6 class="mb-0">Basket</h6>
                    </div>
                </a>
            </div>
            
            <div class="col-lg-2 col-md-4 col-6">
                <a href="{{ route('fields.index', ['category' => 'Badminton']) }}" class="text-decoration-none">
                    <div class="category-card-simple text-center p-3 border rounded hover-shadow">
                        <i class="fas fa-table-tennis fa-2x text-success mb-2"></i>
                        <h6 class="mb-0">Badminton</h6>
                    </div>
                </a>
            </div>
            
            <div class="col-lg-2 col-md-4 col-6">
                <a href="{{ route('fields.index', ['category' => 'Tenis']) }}" class="text-decoration-none">
                    <div class="category-card-simple text-center p-3 border rounded hover-shadow">
                        <i class="fas fa-tennis-ball fa-2x text-info mb-2"></i>
                        <h6 class="mb-0">Tenis</h6>
                    </div>
                </a>
            </div>
            
            <div class="col-lg-2 col-md-4 col-6">
                <a href="{{ route('fields.index', ['category' => 'Voli']) }}" class="text-decoration-none">
                    <div class="category-card-simple text-center p-3 border rounded hover-shadow">
                        <i class="fas fa-volleyball-ball fa-2x text-danger mb-2"></i>
                        <h6 class="mb-0">Voli</h6>
                    </div>
                </a>
            </div>
            
            <div class="col-lg-2 col-md-4 col-6">
                <a href="{{ route('fields.index') }}" class="text-decoration-none">
                    <div class="category-card-simple text-center p-3 border rounded hover-shadow">
                        <i class="fas fa-th-large fa-2x text-secondary mb-2"></i>
                        <h6 class="mb-0">Semua</h6>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-5 bg-light" id="how-it-works">
    <div class="container">
        <div class="text-center mb-5">
            <h4 class="mb-3">Cara Kerja LapangKuy</h4>
            <p class="text-muted">Booking lapangan olahraga favorit Anda dalam 3 langkah mudah</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="card-body">
                        <div class="display-3 text-primary mb-3">
                            <i class="fas fa-search"></i>
                        </div>
                        <h5>1. Cari Lapangan</h5>
                        <p>Temukan lapangan olahraga yang sesuai dengan kebutuhan Anda berdasarkan jenis olahraga, lokasi, dan tanggal.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="card-body">
                        <div class="display-3 text-primary mb-3">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h5>2. Pesan Jadwal</h5>
                        <p>Pilih jadwal yang tersedia sesuai dengan waktu yang Anda inginkan dan lakukan pemesanan dengan mudah.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="card-body">
                        <div class="display-3 text-primary mb-3">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <h5>3. Bayar & Main</h5>
                        <p>Lakukan pembayaran dengan berbagai metode yang tersedia, dan nikmati waktu bermain Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Field Owner Registration Section -->
<section class="py-5" id="field-owner">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <div class="field-owner-content">
                    <span class="badge bg-success mb-2">UNTUK PEMILIK LAPANGAN</span>
                    <h2 class="mb-4">Daftarkan Lapangan Anda di LapangKuy</h2>
                    <p class="lead mb-4">Pemilik lapangan olahraga dapat bergabung dengan platform kami dan mendapatkan keuntungan:</p>
                    
                    <div class="row g-4 mt-2">
                        <div class="col-md-6">
                            <div class="benefit-item text-center p-4 border rounded hover-shadow h-100">
                                <div class="benefit-icon bg-primary-soft rounded-circle p-3 mx-auto mb-3" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-globe fa-2x text-primary"></i>
                                </div>
                                <h5 class="mb-2">Jangkauan Lebih Luas</h5>
                                <p class="text-muted mb-0">Tingkatkan visibilitas lapangan Anda dan dapatkan lebih banyak pelanggan</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="benefit-item text-center p-4 border rounded hover-shadow h-100">
                                <div class="benefit-icon bg-success-soft rounded-circle p-3 mx-auto mb-3" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-calendar-check fa-2x text-success"></i>
                                </div>
                                <h5 class="mb-2">Sistem Booking Otomatis</h5>
                                <p class="text-muted mb-0">Kelola jadwal dan reservasi tanpa kerumitan administrasi manual</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-5">
                        <a href="{{ route('owner.register') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Daftar Sebagai Pemilik Lapangan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h4 class="mb-3">Siap untuk booking lapangan?</h4>
                <p class="mb-lg-0">Mulai cari lapangan olahraga favoritmu sekarang dan nikmati kemudahan booking online!</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('fields.index') }}" class="btn btn-light btn-lg">Cari Lapangan Sekarang</a>
            </div>
        </div>
    </div>
</section>

<style>
.hero-compact {
    background: linear-gradient(135deg, #043E03 0%, #065A04 100%);
}

.stat-card {
    transition: transform 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.hover-shadow {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
}

/* Field Owner Section Styles */
.benefit-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s ease;
}

.benefit-item:hover .benefit-icon {
    transform: scale(1.1);
}

.field-card-home {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border-radius: 12px;
    overflow: hidden;
}

.field-card-home:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.feature-card {
    padding: 1.5rem;
}

.feature-icon {
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.category-card-simple {
    transition: all 0.2s ease;
    background: white;
}

.hover-shadow:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}

/* Field Owner Section Styles */
.field-owner-content {
    position: relative;
    z-index: 2;
}

.field-owner-image {
    position: relative;
}

.bg-primary-soft {
    background-color: rgba(4, 62, 3, 0.1);
}

.bg-success-soft {
    background-color: rgba(25, 135, 84, 0.1);
}

.bg-info-soft {
    background-color: rgba(13, 202, 240, 0.1);
}

.benefit-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.owner-stats-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.field-owner-cards {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.field-owner-cards .row {
    flex-grow: 1;
}

@media (max-width: 991px) {
    .field-owner-cards {
        margin-top: 2rem;
    }
}

@media (max-width: 768px) {
    .hero-stats .col-6 {
        margin-bottom: 1rem;
    }
}

.field-card-home {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.field-card-home:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.12) !important;
}
.field-card-img-container {
    height: 200px; /* Fixed height for image container */
    overflow: hidden;
}
.field-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Ensures image covers the container, might crop */
    transition: transform 0.3s ease;
}
.field-card-home:hover .field-card-img {
    transform: scale(1.05);
}
.fs-tiny {
    font-size: 0.75rem;
}
.text-shadow-sm {
    text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
}
.field-overlay-bottom {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 100%);
}
</style>
@endsection
