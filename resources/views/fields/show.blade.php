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
                
                <!-- Gallery thumbnails (placeholder for now) -->
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

    <!-- Field Details (semua konten digabung tanpa tab) -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-5">
                <div class="card-body p-4">
                    <!-- Deskripsi -->
                    <h5 class="mb-3 fw-bold">Tentang {{ $field->name }}</h5>
                    <p class="lead">{{ $field->description }}</p>

                    <!-- Fasilitas -->
                    <h5 class="mb-4 fw-bold mt-5">Fasilitas Tersedia</h5>
                    <div class="row">
                        @php
                            $facilitiesList = $field->facilities;
                            if (is_string($facilitiesList) && !is_null($facilitiesList)) {
                                $decoded = json_decode($facilitiesList, true);
                                $facilitiesList = is_array($decoded) ? $decoded : [];
                            } elseif (!is_array($facilitiesList)) {
                                $facilitiesList = [];
                            }
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
                        @if(count($facilitiesList) > 0)
                            <div class="col-12 mb-4">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>{{ $field->name }}</strong> dilengkapi dengan berbagai fasilitas untuk mendukung kenyamanan Anda selama bermain.
                                </div>
                            </div>
                            @foreach($facilitiesList as $facility)
                            <div class="col-md-4 col-sm-6 mb-3">
                                <div class="d-flex align-items-center p-3 bg-light rounded shadow-sm hover-effect">
                                    <div class="facility-icon rounded-circle bg-success d-flex align-items-center justify-content-center me-3" style="width: 46px; height: 46px; min-width: 46px;">
                                        <i class="fas {{ isset($facilityIcons[$facility]) ? $facilityIcons[$facility] : 'fa-check' }} text-white"></i>
                                    </div>
                                    <span class="fw-medium">{{ $facility }}</span>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Informasi fasilitas akan segera ditambahkan.
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="facility-note mt-4 p-3 bg-light rounded">
                        <h6 class="fw-bold"><i class="fas fa-exclamation-circle me-2 text-warning"></i>Catatan Fasilitas</h6>
                        <ul class="mb-0">
                            <li>Semua fasilitas tersedia selama jam operasional lapangan</li>
                            <li>Untuk penggunaan fasilitas khusus, silakan hubungi pengelola lapangan</li>
                            <li>Mohon jaga kebersihan dan gunakan fasilitas dengan bijak</li>
                        </ul>
                    </div>

                    <!-- Review -->
                    <h5 class="mb-4 fw-bold mt-5">Review ({{ $totalReviews }})</h5>
                    @if($reviews->count() > 0)
                        <div class="list-group-flush">
                            @foreach($reviews as $review)
                            <div class="list-group-item py-3 border-0">
                                <div class="d-flex align-items-start">
                                    <div class="me-3">
                                        <img src="{{ $review->user->profile_photo_url }}" alt="{{ $review->user->name }}" 
                                             class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                    </div>
                                    <div class="w-100">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0 fw-bold">{{ $review->user->name }}</h6>
                                            <div class="rating-stars">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="mb-1">{{ $review->comment }}</p>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $review->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Belum ada review untuk lapangan ini. Jadilah yang pertama memberikan review!
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection