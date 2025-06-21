@php
// Clear up the duplicate tabs and add the missing Schedule tab content
$fixedShowBlade = <<<'BLADE'
@extends('layouts.app')

@section('title', $field->name)

@section('content')
<div class="container py-5">
    <!-- Field Header -->
    <div class="row mb-5">
        <div class="col-md-8 mb-4">
            <div class="field-gallery card shadow-sm">
                <div class="position-relative">
                    <img src="{{ $field->image_url ?? 'https://placehold.co/800x400/043E03/FFFFFF?text=Lapangan' }}" 
                         class="img-fluid main-image w-100" alt="{{ $field->name }}">
                    @if($field->promo)
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-warning p-2 shadow-sm">
                                <i class="fas fa-fire-alt me-1"></i> Promo
                            </span>
                        </div>
                    @endif
                </div>
                
                <!-- Gallery thumbnails -->
                <div class="gallery-thumbnails p-2 bg-light border-top">
                    <div class="row g-2">
                        @php
                            $galleryImages = $field->gallery ?? [];
                            if (is_string($galleryImages)) {
                                $galleryImages = json_decode($galleryImages, true) ?: [];
                            }
                            // If no gallery images, use main image as first thumbnail
                            if (empty($galleryImages) && $field->image_url) {
                                $galleryImages = [$field->image_url];
                            }
                            // Fill remaining slots with placeholder images if needed
                            while (count($galleryImages) < 4) {
                                $galleryImages[] = 'https://placehold.co/200x150/043E03/FFFFFF?text=View+' . (count($galleryImages) + 1);
                            }
                        @endphp
                        
                        @foreach($galleryImages as $index => $image)
                        <div class="col-3">
                            <img src="{{ $image }}" 
                                 class="img-fluid rounded thumbnail-image" alt="View {{ $index + 1 }}">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="field-info-card card border-0 shadow-sm sticky-top">
                <div class="card-body">
                    <h1 class="h3 mb-3 fw-bold">{{ $field->name }}</h1>
                    
                    <!-- Rating Display -->
                    <div class="mb-3">
                        @php
                            $averageRating = $field->reviews()->avg('rating') ?? 0;
                            $reviewCount = $field->reviews()->count();
                        @endphp
                        <div class="d-flex align-items-center">
                            <div class="rating-stars me-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= round($averageRating) ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                            </div>
                            <span class="text-muted">
                                {{ number_format($averageRating, 1) }} ({{ $reviewCount }} review{{ $reviewCount != 1 ? 's' : '' }})
                            </span>
                        </div>
                    </div>
                    
                    <div class="field-details mb-4">
                        <p class="mb-2 d-flex align-items-start">
                            <i class="fas fa-map-marker-alt me-2 mt-1 text-danger"></i>
                            <span>{{ $field->location }}</span>
                        </p>
                        <p class="mb-2 d-flex align-items-start">
                            <i class="fas fa-tag me-2 mt-1 text-primary"></i>
                            <span>{{ $field->category }}</span>
                        </p>
                        <p class="mb-2 d-flex align-items-start">
                            <i class="fas fa-clock me-2 mt-1 text-success"></i>
                            <span>{{ $field->open_time }} - {{ $field->close_time }} WIB</span>
                        </p>
                        <p class="h4 text-primary fw-bold mb-0 d-flex align-items-center">
                            <i class="fas fa-money-bill-wave me-2 text-success"></i>
                            <span>Rp{{ number_format($field->price,0,',','.') }}/jam</span>
                        </p>
                    </div>
                    
                    @if($field->promo)
                    <div class="alert alert-warning mb-3 d-flex align-items-center">
                        <i class="fas fa-fire me-2"></i>
                        <div>
                            <strong>Promo Spesial!</strong> Lapangan sedang dalam masa promosi
                        </div>
                    </div>
                    @endif
                    
                    <div class="booking-actions d-grid gap-2">
                        <a href="{{ route('bookings.create', $field->id) }}" class="btn btn-success btn-lg">
                            <i class="fas fa-calendar-plus me-2"></i>Booking Sekarang
                        </a>
                        
                        @auth
                            @if(Auth::user()->role === 'admin' || (Auth::user()->isFieldOwner() && $field->owner_id === Auth::id()))
                                <a href="{{ route('fields.edit', $field->id) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-edit me-2"></i>Edit Lapangan
                                </a>
                            @endif
                            
                            @if(Auth::user()->role === 'admin')
                                <form action="{{ route('fields.destroy', $field->id) }}" method="POST" 
                                      onsubmit="return confirm('Hapus lapangan ini?');" class="mt-2">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger w-100">
                                        <i class="fas fa-trash me-2"></i>Hapus Lapangan
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Field Details Tabs -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-5">
                <div class="card-header bg-white pb-0">
                    <!-- Versi desktop -->
                    <ul class="nav nav-tabs card-header-tabs d-none d-md-flex" id="fieldTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active d-flex align-items-center" id="description-tab" data-bs-toggle="tab" 
                                    data-bs-target="#description" type="button" role="tab">
                                <i class="fas fa-info-circle me-1"></i><span>Deskripsi</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center" id="facilities-tab" data-bs-toggle="tab" 
                                    data-bs-target="#facilities" type="button" role="tab">
                                <i class="fas fa-list me-1"></i><span>Fasilitas</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center" id="schedule-tab" data-bs-toggle="tab" 
                                    data-bs-target="#schedule" type="button" role="tab">
                                <i class="fas fa-calendar-alt me-1"></i><span>Jadwal</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center" id="reviews-tab" data-bs-toggle="tab" 
                                    data-bs-target="#reviews" type="button" role="tab">
                                <i class="fas fa-star me-1"></i><span>Review ({{ $totalReviews }})</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center" id="location-tab" data-bs-toggle="tab" 
                                    data-bs-target="#location" type="button" role="tab">
                                <i class="fas fa-map-marker-alt me-1"></i><span>Lokasi</span>
                            </button>
                        </li>
                    </ul>
                    
                    <!-- Versi mobile -->
                    <div class="tab-select d-md-none">
                        <select class="form-select" id="fieldTabsMobile">
                            <option value="description" selected>Deskripsi</option>
                            <option value="facilities">Fasilitas</option>
                            <option value="schedule">Jadwal & Ketersediaan</option>
                            <option value="reviews">Review ({{ $totalReviews }})</option>
                            <option value="location">Lokasi</option>
                        </select>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <div class="tab-content" id="fieldTabsContent">
                        <!-- Description Tab -->
                        <div class="tab-pane fade show active" id="description" role="tabpanel">
                            <h5 class="mb-3 fw-bold">Tentang {{ $field->name }}</h5>
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="lead">{{ $field->description ?? 'Lapangan olahraga berkualitas dengan fasilitas lengkap untuk aktivitas olahraga Anda.' }}</p>
                                    
                                    @if($field->owner)
                                    <div class="mt-4">
                                        <h6 class="fw-bold">Informasi Pemilik Lapangan</h6>
                                        <div class="d-flex align-items-center mt-2">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 50px; height: 50px; min-width: 50px;">
                                                <span class="text-white fw-bold">
                                                    {{ strtoupper(substr($field->owner->name ?? 'A', 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <strong class="d-block">{{ $field->owner->name ?? 'Pemilik Lapangan' }}</strong>
                                                <div class="text-muted">
                                                    <small>
                                                        <i class="fas fa-phone-alt me-1"></i>
                                                        {{ $field->owner->phone ?? 'Nomor tidak tersedia' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="fw-bold mb-3">Informasi Lapangan</h6>
                                            <ul class="list-unstyled mb-0">
                                                <li class="mb-2"><i class="fas fa-futbol me-2 text-success"></i><strong>Kategori:</strong> {{ $field->category }}</li>
                                                <li class="mb-2"><i class="fas fa-users me-2 text-info"></i><strong>Kapasitas:</strong> {{ $field->capacity ?? '22' }} orang</li>
                                                <li class="mb-2"><i class="fas fa-ruler me-2 text-warning"></i><strong>Ukuran:</strong> {{ $field->size ?? 'Standar FIFA' }}</li>
                                                <li class="mb-2"><i class="fas fa-layer-group me-2 text-primary"></i><strong>Jenis Lantai:</strong> {{ $field->surface ?? 'Rumput Sintetis' }}</li>
                                                <li><i class="fas fa-clock me-2 text-dark"></i><strong>Jam Operasional:</strong> {{ $field->open_time }} - {{ $field->close_time }} WIB (Setiap Hari)</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Facilities Tab -->
                        <div class="tab-pane fade" id="facilities" role="tabpanel">
                            <h5 class="mb-4 fw-bold">Fasilitas Tersedia</h5>
                            <div class="row">
                                @php
                                    $facilitiesList = $field->facilities;
                                    // If facilities is a string, try to decode it
                                    if (is_string($facilitiesList) && !is_null($facilitiesList)) {
                                        $decoded = json_decode($facilitiesList, true);
                                        $facilitiesList = is_array($decoded) ? $decoded : [];
                                    } elseif (!is_array($facilitiesList)) {
                                        $facilitiesList = [];
                                    }
                                    
                                    // Icons mapping for common facilities
                                    $facilityIcons = [
                                        'Parkir' => 'fa-parking',
                                        'Toilet' => 'fa-toilet',
                                        'WiFi' => 'fa-wifi',
                                        'Kamar Ganti' => 'fa-tshirt',
                                        'Kantin' => 'fa-utensils',
                                        'Mushola' => 'fa-mosque',
                                        'CCTV' => 'fa-video',
                                        'P3K' => 'fa-first-aid',
                                        'Shower' => 'fa-shower',
                                        'Tribun' => 'fa-users',
                                        'Loker' => 'fa-lock',
                                        'AC' => 'fa-snowflake',
                                        'Tempat Duduk' => 'fa-chair',
                                        'Sound System' => 'fa-volume-up',
                                        'Lampu' => 'fa-lightbulb',
                                        'Area Tunggu' => 'fa-couch',
                                        'Minuman Gratis' => 'fa-coffee'
                                    ];
                                @endphp
                                
                                @php
                                // Tambahkan fasilitas default jika tidak ada data
                                if (empty($facilitiesList)) {
                                    $facilitiesList = [
                                        'Toilet', 
                                        'Parkir', 
                                        'WiFi', 
                                        'Kamar Ganti', 
                                        'Tribun', 
                                        'CCTV'
                                    ];
                                }
                                @endphp
                            
                                @if(count($facilitiesList) > 0)
                                    <div class="col-12 mb-4">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <strong>{{ $field->name }}</strong> dilengkapi dengan berbagai fasilitas untuk mendukung kenyamanan Anda selama bermain.
                                        </div>
                                    </div>
                                    
                                    @foreach($facilitiesList as $facility)
                                    <div class="col-md-4 col-sm-6 mb-3">
                                        <div class="d-flex align-items-center p-3 bg-light rounded shadow-sm hover-effect facility-item">
                                            <div class="facility-icon rounded-circle bg-success d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 46px; height: 46px; min-width: 46px;">
                                                <i class="fas {{ isset($facilityIcons[$facility]) ? $facilityIcons[$facility] : 'fa-check' }} text-white"></i>
                                            </div>
                                            <span class="fw-medium">{{ $facility }}</span>
                                        </div>
                                    </div>
                                    @endforeach

                                    <div class="col-12 mt-3">
                                        <div class="facility-note mt-2 p-3 bg-light rounded">
                                            <h6 class="fw-bold"><i class="fas fa-exclamation-circle me-2 text-warning"></i>Catatan Fasilitas</h6>
                                            <ul class="mb-0">
                                                <li>Semua fasilitas tersedia selama jam operasional lapangan</li>
                                                <li>Untuk penggunaan fasilitas khusus, silakan hubungi pengelola lapangan</li>
                                                <li>Mohon jaga kebersihan dan gunakan fasilitas dengan bijak</li>
                                            </ul>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Informasi fasilitas akan segera ditambahkan.
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Schedule Tab -->
                        <div class="tab-pane fade" id="schedule" role="tabpanel">
                            <h5 class="mb-4 fw-bold">Jadwal & Ketersediaan</h5>
                            
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle me-2"></i>
                                Berikut jadwal ketersediaan lapangan untuk hari ini dan besok. Silakan klik pada slot waktu yang tersedia untuk melakukan pemesanan.
                            </div>

                            <!-- Schedule Navigation Tabs -->
                            <ul class="nav nav-pills mb-4" id="scheduleNavigation" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="today-tab" data-bs-toggle="pill" 
                                            data-bs-target="#today-schedule" type="button" role="tab">
                                        <i class="fas fa-calendar-day me-2"></i>
                                        <span class="d-none d-sm-inline">{{ $schedule['today']['day'] }}</span>
                                        <span>(Hari Ini)</span>
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tomorrow-tab" data-bs-toggle="pill" 
                                            data-bs-target="#tomorrow-schedule" type="button" role="tab">
                                        <i class="fas fa-calendar-plus me-2"></i>
                                        <span class="d-none d-sm-inline">{{ $schedule['tomorrow']['day'] }}</span>
                                        <span>(Besok)</span>
                                    </button>
                                </li>
                            </ul>
                            
                            <!-- Schedule Content -->
                            <div class="tab-content" id="scheduleContent">
                                <!-- Today's Schedule -->
                                <div class="tab-pane fade show active" id="today-schedule" role="tabpanel">
                                    <h6 class="mb-3">
                                        <i class="fas fa-calendar-day me-2 text-primary"></i>
                                        Ketersediaan untuk hari {{ $schedule['today']['display_date'] }}
                                    </h6>
                                    
                                    <div class="schedule-legend mb-3 d-flex">
                                        <div class="me-4 d-flex align-items-center">
                                            <div class="status-indicator available me-2"></div>
                                            <span>Tersedia</span>
                                        </div>
                                        <div class="me-4 d-flex align-items-center">
                                            <div class="status-indicator booked me-2"></div>
                                            <span>Sudah Dibooking</span>
                                        </div>
                                    </div>

                                    @if(count($schedule['today']['slots']) > 0)
                                        <div class="row g-3">
                                            @foreach($schedule['today']['slots'] as $slot)
                                                <div class="col-6 col-md-4 col-lg-3">
                                                    <div class="time-slot-card position-relative 
                                                            {{ $slot['available'] ? 'available' : 'booked' }}"
                                                         data-time="{{ $slot['time'] }}">
                                                        <div class="time">
                                                            <i class="fas fa-clock me-2"></i>{{ $slot['time'] }}
                                                        </div>
                                                        <div class="price">Rp{{ number_format($slot['price'],0,',','.') }}</div>
                                                        
                                                        @if($slot['available'])
                                                            <a href="{{ route('bookings.create', ['field_id' => $field->id, 'date' => $schedule['today']['date'], 'time' => $slot['time']]) }}" 
                                                               class="btn-book">
                                                                <i class="fas fa-check-circle me-1"></i> Booking
                                                            </a>
                                                        @else
                                                            <div class="status-badge booked">
                                                                <i class="fas fa-times-circle me-1"></i> Sudah Dibooking
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            Tidak ada slot waktu yang tersedia untuk hari ini.
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Tomorrow's Schedule -->
                                <div class="tab-pane fade" id="tomorrow-schedule" role="tabpanel">
                                    <h6 class="mb-3">
                                        <i class="fas fa-calendar-plus me-2 text-success"></i>
                                        Ketersediaan untuk hari {{ $schedule['tomorrow']['display_date'] }}
                                    </h6>
                                    
                                    <div class="schedule-legend mb-3 d-flex">
                                        <div class="me-4 d-flex align-items-center">
                                            <div class="status-indicator available me-2"></div>
                                            <span>Tersedia</span>
                                        </div>
                                        <div class="me-4 d-flex align-items-center">
                                            <div class="status-indicator booked me-2"></div>
                                            <span>Sudah Dibooking</span>
                                        </div>
                                    </div>

                                    @if(count($schedule['tomorrow']['slots']) > 0)
                                        <div class="row g-3">
                                            @foreach($schedule['tomorrow']['slots'] as $slot)
                                                <div class="col-6 col-md-4 col-lg-3">
                                                    <div class="time-slot-card position-relative 
                                                            {{ $slot['available'] ? 'available' : 'booked' }}"
                                                         data-time="{{ $slot['time'] }}">
                                                        <div class="time">
                                                            <i class="fas fa-clock me-2"></i>{{ $slot['time'] }}
                                                        </div>
                                                        <div class="price">Rp{{ number_format($slot['price'],0,',','.') }}</div>
                                                        
                                                        @if($slot['available'])
                                                            <a href="{{ route('bookings.create', ['field_id' => $field->id, 'date' => $schedule['tomorrow']['date'], 'time' => $slot['time']]) }}" 
                                                               class="btn-book">
                                                                <i class="fas fa-check-circle me-1"></i> Booking
                                                            </a>
                                                        @else
                                                            <div class="status-badge booked">
                                                                <i class="fas fa-times-circle me-1"></i> Sudah Dibooking
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            Tidak ada slot waktu yang tersedia untuk besok.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Reviews Tab -->
                        <div class="tab-pane fade" id="reviews" role="tabpanel">
                            <h5 class="mb-4 fw-bold">
                                <i class="fas fa-star text-warning me-2"></i>Review Pelanggan
                            </h5>
                            
                            <!-- Review Statistics -->
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body text-center">
                                            <h2 class="mb-0 fw-bold">{{ number_format($averageRating, 1) }}</h2>
                                            <div class="rating-stars my-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= round($averageRating) ? 'text-warning' : 'text-muted' }}"></i>
                                                @endfor
                                            </div>
                                            <p class="mb-0 text-muted">{{ $totalReviews }} review</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <!-- If we have review breakdown/stats, it would go here -->
                                </div>
                            </div>

                            <!-- Review List -->
                            <div class="review-list">
                                @forelse($field->reviews as $review)
                                    <div class="review-item card border-0 bg-light mb-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                        style="width: 40px; height: 40px; min-width: 40px;">
                                                        <span class="text-white fw-bold">
                                                            {{ strtoupper(substr($review->user->name ?? 'A', 0, 1)) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold">{{ $review->user->name ?? 'Anonim' }}</h6>
                                                        <div class="rating-stars my-1">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }} small"></i>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-muted small">
                                                    @if($review->created_at)
                                                        {{ \Carbon\Carbon::parse($review->created_at)->format('d M Y') }}
                                                    @endif
                                                </div>
                                            </div>
                                            <p class="mb-0">{{ $review->comment }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Belum ada review untuk lapangan ini.
                                    </div>
                                    
                                    <div class="text-center mt-3">
                                        <a href="{{ route('bookings.create', $field->id) }}" class="btn btn-primary">
                                            <i class="fas fa-thumbs-up me-2"></i>Jadilah yang Pertama Memberi Review
                                        </a>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        
                        <!-- Location Tab -->
                        <div class="tab-pane fade" id="location" role="tabpanel">
                            <h5 class="mb-4 fw-bold">
                                <i class="fas fa-map-marker-alt text-danger me-2"></i>Lokasi {{ $field->name }}
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="location-details">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6 class="fw-bold mb-3">Alamat Lengkap:</h6>
                                                <p class="mb-3">{{ $field->location }}</p>
                                                
                                                <h6 class="fw-bold mb-2">Petunjuk Arah:</h6>
                                                <ul class="mb-4">
                                                    <li>Dari Jalan Utama, belok ke arah {{ $field->name }}</li>
                                                    <li>Lokasi berada 500m dari persimpangan terdekat</li>
                                                    <li>Tersedia petunjuk arah menuju lokasi</li>
                                                </ul>
                                                
                                                <a href="https://maps.google.com/?q={{ urlencode($field->location) }}" class="btn btn-primary" target="_blank">
                                                    <i class="fas fa-directions me-2"></i>Petunjuk Arah
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!-- Embedded Google Maps (or placeholder) -->
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <div id="map-placeholder" class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 300px;">
                                            @if(!empty($field->map_embed))
                                                {!! $field->map_embed !!}
                                            @else
                                                <div class="text-center p-4">
                                                    <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                                                    <h6>Peta Interaktif</h6>
                                                    <p class="small text-muted mb-0">Peta akan ditampilkan di sini.</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Similar Fields Section -->
    @if($similarFields->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="h4 mb-0 fw-bold">Lapangan Serupa</h3>
                <a href="{{ route('fields.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-list me-1"></i>Lihat Semua
                </a>
            </div>
            
            <div class="row">
                @foreach($similarFields as $similarField)
                <div class="col-md-3 col-6 mb-4">
                    <div class="card field-card h-100 border-0 shadow-sm">
                        <img src="{{ $similarField->image_url ?? 'https://placehold.co/300x150/043E03/FFFFFF?text=Lapangan' }}" 
                             class="card-img-top" alt="{{ $similarField->name }}">
                        <div class="card-body">
                            <h5 class="card-title mb-1 fw-bold">{{ mb_strlen($similarField->name) > 18 ? mb_substr($similarField->name, 0, 18) . '...' : $similarField->name }}</h5>
                            <p class="card-text text-muted small mb-2">
                                <i class="fas fa-map-marker-alt me-1 text-danger"></i>
                                {{ mb_strlen($similarField->location) > 25 ? mb_substr($similarField->location, 0, 25) . '...' : $similarField->location }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-primary fw-bold">
                                    Rp{{ number_format($similarField->price,0,',','.') }}
                                </span>
                                <a href="{{ route('fields.show', $similarField->id) }}" class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-eye me-1"></i>Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<style>
/* Time Slot Cards */
.time-slot-card {
    padding: 1rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    background-color: #f8f9fa;
    height: 100%;
}

.time-slot-card.available {
    border-left: 4px solid #28a745;
}

.time-slot-card.available:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    cursor: pointer;
}

.time-slot-card.booked {
    border-left: 4px solid #dc3545;
    opacity: 0.8;
    background-color: #f5f5f5;
}

.time-slot-card .time {
    font-weight: 600;
    font-size: 1.1rem;
}

.time-slot-card .price {
    color: #0d6efd;
    margin-top: 0.25rem;
    font-weight: 500;
}

.time-slot-card .btn-book {
    display: inline-block;
    margin-top: 0.75rem;
    padding: 0.25rem 0.75rem;
    background-color: #28a745;
    color: white;
    border-radius: 0.25rem;
    font-size: 0.85rem;
    text-decoration: none;
    transition: all 0.2s ease;
}

.time-slot-card .btn-book:hover {
    background-color: #218838;
    transform: scale(1.05);
}

.time-slot-card .status-badge {
    margin-top: 0.75rem;
    padding: 0.25rem 0.75rem;
    border-radius: 0.25rem;
    font-size: 0.85rem;
    text-align: center;
}

.time-slot-card .status-badge.booked {
    background-color: #f8d7da;
    color: #721c24;
}

.schedule-legend .status-indicator {
    width: 16px;
    height: 16px;
    border-radius: 50%;
}

.schedule-legend .status-indicator.available {
    background-color: #28a745;
}

.schedule-legend .status-indicator.booked {
    background-color: #dc3545;
}

/* Facility Items Hover Effect */
.facility-item {
    transition: all 0.3s ease;
}

.facility-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile Tabs Navigation
    const mobileTabSelect = document.getElementById('fieldTabsMobile');
    if (mobileTabSelect) {
        mobileTabSelect.addEventListener('change', function() {
            const tabId = this.value;
            const tabElement = document.querySelector(`button[data-bs-target="#${tabId}"]`);
            if (tabElement) {
                bootstrap.Tab.getOrCreateInstance(tabElement).show();
            }
        });
    }
});
</script>
@endsection
BLADE;

// Output the fixed blade file to storage/app/fixed_show_blade.php
\Storage::disk('local')->put('fixed_show_blade.php', $fixedShowBlade);

// Instruct the user what to do with this fixed file
echo "<div class='alert alert-success'>
    <p><strong>Perbaikan Selesai!</strong></p>
    <p>File perbaikan untuk show.blade.php telah dibuat di storage/app/fixed_show_blade.php</p>
    <p>Untuk menerapkan perbaikan ini:</p>
    <ol>
        <li>Salin isi file dari storage/app/fixed_show_blade.php</li>
        <li>Tempel ke resources/views/fields/show.blade.php</li>
    </ol>
    <p>Atau jalankan perintah berikut di terminal/command prompt:</p>
    <code>cp storage/app/fixed_show_blade.php resources/views/fields/show.blade.php</code>
</div>";
