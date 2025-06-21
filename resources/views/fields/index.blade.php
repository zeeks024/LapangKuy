@extends('layouts.app')
@section('title', 'Daftar Lapangan - LapangKuy')
@section('content')

<!-- Compact Search Section -->
<div class="bg-light py-4 border-bottom">
    <div class="container">
        <div class="row align-items-center mb-3">
            <div class="col">
                <h2 class="h4 mb-1 text-primary fw-bold">Daftar Lapangan</h2>
                <p class="text-muted mb-0 small">{{ $fields->total() }} lapangan tersedia</p>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm">
            <div class="card-body py-3">
                <form method="GET" action="{{ route('fields.index') }}" id="searchForm">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <input type="text" class="form-control form-control-sm" name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Cari lapangan...">
                        </div>
                        <div class="col-md-2">
                            <select class="form-select form-select-sm" name="category">
                                <option value="">Kategori</option>
                                <option value="Futsal" {{ request('category') == 'Futsal' ? 'selected' : '' }}>Futsal</option>
                                <option value="Badminton" {{ request('category') == 'Badminton' ? 'selected' : '' }}>Badminton</option>
                                <option value="Basket" {{ request('category') == 'Basket' ? 'selected' : '' }}>Basket</option>
                                <option value="Tenis" {{ request('category') == 'Tenis' ? 'selected' : '' }}>Tenis</option>
                                <option value="Voli" {{ request('category') == 'Voli' ? 'selected' : '' }}>Voli</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select form-select-sm" name="max_price">
                                <option value="">Max Harga</option>
                                <option value="50000" {{ request('max_price') == '50000' ? 'selected' : '' }}>< 50k</option>
                                <option value="100000" {{ request('max_price') == '100000' ? 'selected' : '' }}>< 100k</option>
                                <option value="150000" {{ request('max_price') == '150000' ? 'selected' : '' }}>< 150k</option>
                                <option value="200000" {{ request('max_price') == '200000' ? 'selected' : '' }}>< 200k</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select form-select-sm" name="location">
                                <option value="">Lokasi</option>                                <option value="Jakarta Pusat" {{ request('location') == 'Jakarta Pusat' ? 'selected' : '' }}>Jakarta Pusat</option>
                                <option value="Jakarta Utara" {{ request('location') == 'Jakarta Utara' ? 'selected' : '' }}>Jakarta Utara</option>
                                <option value="Jakarta Selatan" {{ request('location') == 'Jakarta Selatan' ? 'selected' : '' }}>Jakarta Selatan</option>
                                <option value="Jakarta Timur" {{ request('location') == 'Jakarta Timur' ? 'selected' : '' }}>Jakarta Timur</option>
                                <option value="Jakarta Barat" {{ request('location') == 'Jakarta Barat' ? 'selected' : '' }}>Jakarta Barat</option>
                                <option value="Bekasi" {{ request('location') == 'Bekasi' ? 'selected' : '' }}>Bekasi</option>
                                <option value="Tangerang" {{ request('location') == 'Tangerang' ? 'selected' : '' }}>Tangerang</option>
                                <option value="Depok" {{ request('location') == 'Depok' ? 'selected' : '' }}>Depok</option>
                                <option value="Semarang" {{ request('location') == 'Semarang' ? 'selected' : '' }}>Semarang</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <div class="d-flex gap-1">
                                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                                    <i class="fas fa-search"></i>
                                </button>
                                <a href="{{ route('fields.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-refresh"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Results Section -->
<section class="py-4">
    <div class="container">
        <!-- Results Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="mb-1">
                    @if(request()->hasAny(['search', 'category', 'max_price', 'location']))
                        Hasil Pencarian
                    @else
                        Semua Lapangan
                    @endif
                </h5>
                <small class="text-muted">
                    {{ $fields->total() }} lapangan ditemukan
                    @if(request('search'))
                        untuk "{{ request('search') }}"
                    @endif
                </small>
            </div>
            
            <!-- Sort Options -->
            <div class="d-flex align-items-center">
                <label class="form-label me-2 mb-0 small">Urutkan:</label>
                <select class="form-select form-select-sm" name="sort" onchange="updateSort(this.value)" style="width: auto;">
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                    <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                </select>
            </div>
        </div>        <!-- Active Filters -->
        @if(request()->hasAny(['search', 'category', 'max_price', 'location']))
        <div class="active-filters mb-3">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <small class="fw-semibold text-muted">Filter aktif:</small>
                
                @if(request('search'))
                <span class="badge bg-primary">
                    {{ request('search') }}
                    <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="text-white ms-1">&times;</a>
                </span>
                @endif
                
                @if(request('category'))
                <span class="badge bg-info">
                    {{ request('category') }}
                    <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="text-white ms-1">&times;</a>
                </span>
                @endif
                
                @if(request('max_price'))
                <span class="badge bg-success">
                    < Rp {{ number_format(request('max_price'), 0, ',', '.') }}
                    <a href="{{ request()->fullUrlWithQuery(['max_price' => null]) }}" class="text-white ms-1">&times;</a>
                </span>
                @endif
                
                @if(request('location'))
                <span class="badge bg-warning text-dark">
                    {{ request('location') }}
                    <a href="{{ request()->fullUrlWithQuery(['location' => null]) }}" class="text-dark ms-1">&times;</a>
                </span>
                @endif
                
                <a href="{{ route('fields.index') }}" class="btn btn-outline-danger btn-sm py-0">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </div>
        @endif

        <!-- Fields Grid -->
        @if($fields->count() > 0)
            <div class="row g-3">
                @foreach($fields as $field)
                <div class="col-lg-4 col-md-6">
                    <div class="card field-card h-100 border-0 shadow-sm hover-shadow">
                        <div class="position-relative">
                            <img src="{{ $field->image_url ?? 'https://placehold.co/400x200/043E03/FFFFFF?text='.$field->name }}" 
                                 class="card-img-top" alt="{{ $field->name }}" style="height: 180px; object-fit: cover;">
                            
                            @if($field->promo)
                            <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                                <i class="fas fa-fire me-1"></i>Promo
                            </span>
                            @endif
                            
                            <!-- Rating Badge -->
                            @php
                                $avgRating = $field->reviews()->avg('rating') ?? 0;
                                $reviewCount = $field->reviews()->count();
                            @endphp
                            @if($reviewCount > 0)
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-white text-dark shadow-sm">
                                    <i class="fas fa-star text-warning me-1"></i>{{ number_format($avgRating, 1) }}
                                </span>
                            </div>
                            @endif
                        </div>
                        
                        <div class="card-body p-3 d-flex flex-column">
                            <div class="mb-2">
                                <h6 class="card-title mb-1 fw-bold">{{ $field->name }}</h6>
                                <p class="card-text text-muted small mb-1">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $field->location }}
                                </p>
                                <p class="card-text text-muted small mb-0">
                                    <i class="fas fa-tag me-1"></i>{{ $field->category }}
                                </p>
                            </div>
                            
                            <!-- Facilities Preview -->
                            @php
                                $facilitiesList = $field->facilities;
                                // Safe handling for facilities data
                                if (is_string($facilitiesList) && !is_null($facilitiesList)) {
                                    $decoded = json_decode($facilitiesList, true);
                                    $facilitiesList = is_array($decoded) ? $decoded : [];
                                } elseif (!is_array($facilitiesList)) {
                                    $facilitiesList = [];
                                }
                            @endphp
                            @if(count($facilitiesList) > 0)
                            <div class="facilities-preview mb-2">
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach(array_slice($facilitiesList, 0, 2) as $facility)
                                    <span class="badge bg-light text-dark small">{{ $facility }}</span>
                                    @endforeach
                                    @if(count($facilitiesList) > 2)
                                    <span class="badge bg-secondary small">+{{ count($facilitiesList) - 2 }}</span>
                                    @endif
                                </div>
                            </div>
                            @endif
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="price-info">
                                        <h6 class="text-primary fw-bold mb-0">
                                            Rp{{ number_format($field->price,0,',','.') }}/jam
                                        </h6>
                                    </div>
                                    @if($reviewCount > 0)
                                    <small class="text-muted">{{ $reviewCount }} review{{ $reviewCount > 1 ? 's' : '' }}</small>
                                    @endif
                                </div>
                                
                                <div class="d-grid gap-1">
                                    <div class="row g-1">
                                        <div class="col-6">
                                            <a href="{{ route('fields.show', $field->id) }}" class="btn btn-outline-primary btn-sm w-100">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <a href="{{ route('bookings.create', $field->id) }}" class="btn btn-primary btn-sm w-100">
                                                <i class="fas fa-calendar-plus"></i> Booking
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $fields->withQueryString()->links() }}
            </div>
        @else
            <!-- No Results -->
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-search fa-4x text-muted"></i>
                </div>
                <h5 class="text-muted mb-3">Tidak Ada Lapangan Ditemukan</h5>
                <p class="text-muted mb-4">
                    Maaf, tidak ada lapangan yang sesuai dengan pencarian Anda.
                </p>
                <a href="{{ route('fields.index') }}" class="btn btn-primary">
                    <i class="fas fa-refresh me-1"></i>Lihat Semua Lapangan
                </a>
            </div>
        @endif
    </div>
</section>

<style>
.hero-search-section {
    background: linear-gradient(135deg, #043E03 0%, #065A04 100%);
}

.search-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
}

.field-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border-radius: 12px;
    overflow: hidden;
}

.hover-shadow:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.active-filters .badge a {
    text-decoration: none;
}

.active-filters .badge a:hover {
    background-color: rgba(255,255,255,0.2);
    border-radius: 50%;
}

.facilities-preview .badge {
    font-size: 0.7rem;
}

.card-img-top {
    border-radius: 12px 12px 0 0;
}

@media (max-width: 768px) {
    .row.g-3 {
        margin: 0 -8px;
    }
    
    .row.g-3 > * {
        padding: 0 8px;
        margin-bottom: 16px;
    }
}
</style>

<script>
function updateSort(sortValue) {
    const url = new URL(window.location);
    url.searchParams.set('sort', sortValue);
    window.location.href = url.toString();
}

// Auto-submit search form on filter change
document.addEventListener('DOMContentLoaded', function() {
    const filterSelects = document.querySelectorAll('#searchForm select');
    
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            if (this.name !== 'sort') {
                document.getElementById('searchForm').submit();
            }
        });
    });
    
    // Search input with debounce
    const searchInput = document.querySelector('input[name="search"]');
    let searchTimeout;
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 3 || this.value.length === 0) {
                    document.getElementById('searchForm').submit();
                }
            }, 500);
        });
    }
});
</script>
@endsection
