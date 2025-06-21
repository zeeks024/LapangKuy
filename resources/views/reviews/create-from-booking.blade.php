@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">                <div class="card-header">
                    <h4>Berikan Ulasan untuk {{ $booking->field ? $booking->field->name : 'Lapangan' }}</h4>
                    <p class="mb-0 text-muted">Booking: {{ $booking->booking_code }} - {{ $booking->date }}</p>
                </div>

                <div class="card-body">                    <!-- Field Information -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            @if($booking->field && $booking->field->image_url)
                                <img src="{{ $booking->field->image_url }}" 
                                     class="img-fluid rounded" alt="{{ $booking->field->name }}">
                            @else
                                <div class="bg-light p-4 rounded text-center">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                    <p class="mt-2 mb-0">No Image</p>
                                </div>
                            @endif
                        </div>                        <div class="col-md-8">
                            <h5>{{ $booking->field ? $booking->field->name : 'Lapangan' }}</h5>
                            <p class="text-muted">{{ $booking->field ? $booking->field->location : 'Lokasi tidak tersedia' }}</p>
                            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($booking->date)->format('d F Y') }}</p>
                            <p><strong>Waktu:</strong> {{ $booking->start_time }} - {{ $booking->end_time }}</p>
                            <p><strong>Durasi:</strong> {{ $booking->duration }} jam</p>
                        </div>
                    </div>

                    <!-- Review Form -->                    <form method="POST" action="{{ route('reviews.store') }}">
                        @csrf
                        <input type="hidden" name="field_id" value="{{ $booking->field ? $booking->field->id : '' }}">
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                        <div class="form-group mb-3">
                            <label for="rating" class="form-label">Rating <span class="text-danger">*</span></label>
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="star-label">
                                        <input type="radio" name="rating" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }} required>
                                        <i class="fas fa-star star-icon" data-rating="{{ $i }}"></i>
                                    </label>
                                @endfor
                            </div>
                            @error('rating')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="comment" class="form-label">Komentar <span class="text-danger">*</span></label>
                            <textarea name="comment" id="comment" class="form-control" rows="4" 
                                      placeholder="Bagikan pengalaman Anda menggunakan lapangan ini..." required>{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="pros" class="form-label">Kelebihan</label>
                            <textarea name="pros" id="pros" class="form-control" rows="2" 
                                      placeholder="Apa yang Anda sukai dari lapangan ini?">{{ old('pros') }}</textarea>
                            @error('pros')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="cons" class="form-label">Kekurangan</label>
                            <textarea name="cons" id="cons" class="form-control" rows="2" 
                                      placeholder="Apa yang perlu diperbaiki dari lapangan ini?">{{ old('cons') }}</textarea>
                            @error('cons')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('user.dashboard') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Kirim Ulasan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rating-stars {
    display: flex;
    gap: 5px;
    margin-bottom: 10px;
}

.star-label {
    cursor: pointer;
    margin: 0;
}

.star-label input[type="radio"] {
    display: none;
}

.star-icon {
    font-size: 1.5rem;
    color: #ddd;
    transition: color 0.2s;
}

.star-label:hover .star-icon,
.star-label:hover ~ .star-label .star-icon {
    color: #ffc107;
}

.star-label input[type="radio"]:checked ~ .star-icon,
.star-label input[type="radio"]:checked ~ .star-label .star-icon {
    color: #ffc107;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star-icon');
    const radioInputs = document.querySelectorAll('input[name="rating"]');
    
    stars.forEach((star, index) => {
        star.addEventListener('click', function() {
            radioInputs[index].checked = true;
            updateStars(index + 1);
        });
        
        star.addEventListener('mouseover', function() {
            updateStars(index + 1);
        });
    });
    
    document.querySelector('.rating-stars').addEventListener('mouseleave', function() {
        const checkedInput = document.querySelector('input[name="rating"]:checked');
        if (checkedInput) {
            updateStars(parseInt(checkedInput.value));
        } else {
            updateStars(0);
        }
    });
    
    function updateStars(rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.style.color = '#ffc107';
            } else {
                star.style.color = '#ddd';
            }
        });
    }
    
    // Initialize stars based on old input
    const checkedInput = document.querySelector('input[name="rating"]:checked');
    if (checkedInput) {
        updateStars(parseInt(checkedInput.value));
    }
});
</script>
@endsection
