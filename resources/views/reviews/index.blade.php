@extends('layouts.app')

@section('title', 'Review Saya')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-0">Review Saya</h1>                <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Booking Saya
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            @if($reviews->count() > 0)
                <div class="row">
                    @foreach($reviews as $review)
                    <div class="col-lg-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title mb-0">{{ $review->field->name }}</h5>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" 
                                                data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('reviews.edit', $review) }}">
                                                    <i class="fas fa-edit me-1"></i>Edit Review
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('reviews.destroy', $review) }}" method="POST" 
                                                      class="d-inline" onsubmit="return confirm('Yakin ingin menghapus review ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="fas fa-trash me-1"></i>Hapus Review
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="rating-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                        <span class="ms-2 text-muted">({{ $review->rating }}/5)</span>
                                    </div>
                                </div>
                                
                                @if($review->comment)
                                <p class="card-text">{{ $review->comment }}</p>
                                @endif
                                
                                <div class="d-flex justify-content-between align-items-center text-muted small">
                                    <span>
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $review->field->location }}
                                    </span>
                                    <span>{{ $review->created_at->format('d M Y') }}</span>
                                </div>
                                
                                <div class="mt-3">
                                    <a href="{{ route('fields.show', $review->field) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>Lihat Lapangan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $reviews->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-star fa-4x text-muted"></i>
                    </div>
                    <h3 class="text-muted">Belum Ada Review</h3>
                    <p class="text-muted mb-4">Anda belum memberikan review untuk lapangan manapun.</p>
                    <a href="{{ route('fields.index') }}" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Cari Lapangan
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.rating-stars {
    display: flex;
    align-items: center;
}

.rating-stars i {
    font-size: 1.1rem;
    margin-right: 2px;
}

.card {
    transition: transform 0.2s ease-in-out;
    border: 1px solid #e9ecef;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
}

.dropdown-toggle::after {
    display: none;
}
</style>
@endsection
