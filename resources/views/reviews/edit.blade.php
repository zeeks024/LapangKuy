@extends('layouts.app')

@section('title', 'Edit Review')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Review untuk {{ $review->field->name }}</h4>
                </div>
                <div class="card-body">
                    <!-- Field Summary -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <img src="{{ $review->field->image_url ?? 'https://placehold.co/300x200/043E03/FFFFFF?text='.$review->field->name }}" 
                                 class="img-fluid rounded" alt="{{ $review->field->name }}">
                        </div>
                        <div class="col-md-8">
                            <h5 class="mb-2">{{ $review->field->name }}</h5>
                            <p class="text-muted mb-1">
                                <i class="fas fa-map-marker-alt me-1"></i>{{ $review->field->location }}
                            </p>
                            <p class="text-muted mb-1">
                                <i class="fas fa-tag me-1"></i>{{ $review->field->category }}
                            </p>
                            <p class="text-primary mb-1">
                                <strong>Rp {{ number_format($review->field->price, 0, ',', '.') }}/jam</strong>
                            </p>
                            <div class="text-muted small">
                                <i class="fas fa-clock me-1"></i>Review dibuat: {{ $review->created_at->format('d M Y, H:i') }}
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Review Edit Form -->
                    <form method="POST" action="{{ route('reviews.update', $review) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="form-label">Rating *</label>
                            <div class="rating-input d-flex align-items-center">
                                <div class="star-rating me-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" 
                                               {{ old('rating', $review->rating) == $i ? 'checked' : '' }} required>
                                        <label for="star{{ $i }}" class="star-label">
                                            <i class="fas fa-star"></i>
                                        </label>
                                    @endfor
                                </div>
                                <span class="rating-text text-muted">
                                    @php
                                        $ratingTexts = [1 => 'Sangat Buruk', 2 => 'Buruk', 3 => 'Cukup', 4 => 'Baik', 5 => 'Sangat Baik'];
                                    @endphp
                                    {{ $ratingTexts[old('rating', $review->rating)] }}
                                </span>
                            </div>
                            @error('rating')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="comment" class="form-label">Komentar</label>
                            <textarea class="form-control @error('comment') is-invalid @enderror" 
                                      id="comment" name="comment" rows="5" 
                                      placeholder="Bagikan pengalaman Anda menggunakan lapangan ini...">{{ old('comment', $review->comment) }}</textarea>
                            <div class="form-text">Update komentar Anda untuk membantu pengguna lain (opsional)</div>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Current Review Info -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading"><i class="fas fa-info-circle me-1"></i>Review Saat Ini:</h6>
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                </div>
                                <span>({{ $review->rating }}/5)</span>
                            </div>
                            @if($review->comment)
                                <p class="mb-0 small">"{{ $review->comment }}"</p>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('reviews.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Kembali ke Review Saya
                            </a>
                            <div>
                                <a href="{{ route('fields.show', $review->field) }}" class="btn btn-outline-primary me-2">
                                    <i class="fas fa-eye me-1"></i>Lihat Lapangan
                                </a>
                                <button type="submit" class="btn btn-warning text-dark">
                                    <i class="fas fa-save me-1"></i>Update Review
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.star-rating input {
    display: none;
}

.star-label {
    color: #ddd;
    font-size: 1.5rem;
    cursor: pointer;
    transition: color 0.2s ease-in-out;
    margin-right: 0.25rem;
}

.star-label:hover,
.star-rating input:checked ~ .star-label {
    color: #ffc107;
}

.star-rating input:checked ~ .star-label:hover,
.star-rating input:checked ~ .star-label {
    color: #ffc107;
}

.card {
    transition: transform 0.2s ease-in-out;
}

.rating-input {
    padding: 1rem;
    background-color: #f8f9fa;
    border-radius: 0.5rem;
    border: 1px solid #e9ecef;
}

.rating-text {
    font-size: 0.9rem;
    font-weight: 600;
}

.alert-info {
    background-color: #e3f2fd;
    border-color: #90caf9;
    color: #1565c0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star-rating input');
    const ratingText = document.querySelector('.rating-text');
    
    const ratingTexts = {
        1: 'Sangat Buruk',
        2: 'Buruk', 
        3: 'Cukup',
        4: 'Baik',
        5: 'Sangat Baik'
    };
    
    stars.forEach(star => {
        star.addEventListener('change', function() {
            const rating = this.value;
            ratingText.textContent = ratingTexts[rating];
            ratingText.className = 'rating-text text-warning';
        });
    });
});
</script>
@endsection
