@extends('layouts.app')

@section('title', 'Berikan Review')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-star me-2"></i>Berikan Review untuk {{ $field->name }}</h4>
                </div>
                <div class="card-body">
                    <!-- Field Summary -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <img src="{{ $field->image_url ?? 'https://placehold.co/300x200/043E03/FFFFFF?text='.$field->name }}" 
                                 class="img-fluid rounded" alt="{{ $field->name }}">
                        </div>
                        <div class="col-md-8">
                            <h5 class="mb-2">{{ $field->name }}</h5>
                            <p class="text-muted mb-1">
                                <i class="fas fa-map-marker-alt me-1"></i>{{ $field->location }}
                            </p>
                            <p class="text-muted mb-1">
                                <i class="fas fa-tag me-1"></i>{{ $field->category }}
                            </p>
                            <p class="text-primary mb-0">
                                <strong>Rp {{ number_format($field->price, 0, ',', '.') }}/jam</strong>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <!-- Review Form -->
                    <form method="POST" action="{{ route('reviews.store') }}">
                        @csrf
                        <input type="hidden" name="field_id" value="{{ $field->id }}">
                        
                        <div class="mb-4">
                            <label class="form-label">Rating *</label>
                            <div class="rating-input d-flex align-items-center">
                                <div class="star-rating me-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" 
                                               {{ old('rating') == $i ? 'checked' : '' }} required>
                                        <label for="star{{ $i }}" class="star-label">
                                            <i class="fas fa-star"></i>
                                        </label>
                                    @endfor
                                </div>
                                <span class="rating-text text-muted">Pilih rating Anda</span>
                            </div>
                            @error('rating')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="comment" class="form-label">Komentar</label>
                            <textarea class="form-control @error('comment') is-invalid @enderror" 
                                      id="comment" name="comment" rows="5" 
                                      placeholder="Bagikan pengalaman Anda menggunakan lapangan ini...">{{ old('comment') }}</textarea>
                            <div class="form-text">Ceritakan pengalaman Anda untuk membantu pengguna lain (opsional)</div>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('fields.show', $field) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Kembali ke Lapangan
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i>Kirim Review
                            </button>
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
            ratingText.className = 'rating-text text-warning fw-bold';
        });
    });
});
</script>
@endsection
