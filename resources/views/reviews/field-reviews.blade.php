@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4><i class="fas fa-star"></i> Ulasan untuk {{ $field->name }}</h4>
                            <p class="mb-0 text-muted">{{ $field->location }}</p>
                        </div>
                        <div class="text-end">
                            @if($averageRating > 0)
                                <div class="mb-1">
                                    <span class="h4 text-warning">{{ number_format($averageRating, 1) }}</span>
                                    <div class="d-inline-block ms-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $averageRating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <small class="text-muted">{{ $reviews->count() }} ulasan</small>
                            @else
                                <span class="text-muted">Belum ada ulasan</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if($reviews->count() > 0)
                        <!-- Rating Summary -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="display-4 text-warning">{{ number_format($averageRating, 1) }}</div>
                                    <div class="mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $averageRating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                    </div>
                                    <p class="text-muted">{{ $reviews->count() }} ulasan</p>
                                </div>
                            </div>
                            <div class="col-md-8">
                                @for($rating = 5; $rating >= 1; $rating--)
                                    @php
                                        $ratingCount = $reviews->where('rating', $rating)->count();
                                        $percentage = $reviews->count() > 0 ? ($ratingCount / $reviews->count()) * 100 : 0;
                                    @endphp
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="me-2">{{ $rating }} <i class="fas fa-star text-warning"></i></span>
                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                            <div class="progress-bar bg-warning" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <span class="text-muted">{{ $ratingCount }}</span>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <hr>

                        <!-- Individual Reviews -->
                        <div class="reviews-list">
                            @foreach($reviews as $review)
                                <div class="review-item mb-4 p-3 border rounded">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <span class="text-white fw-bold">
                                                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $review->user->name }}</h6>
                                                <div class="mb-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                    @endfor
                                                </div>
                                                <small class="text-muted">
                                                    {{ $review->created_at->diffForHumans() }}
                                                    @if($review->booking)
                                                        • Booking: {{ $review->booking->booking_code }}
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="review-content">
                                        <p class="mb-2">{{ $review->comment }}</p>
                                        
                                        @if($review->pros)
                                            <div class="mb-2">
                                                <small class="text-success"><i class="fas fa-thumbs-up"></i> <strong>Kelebihan:</strong></small>
                                                <p class="mb-1 ms-3">{{ $review->pros }}</p>
                                            </div>
                                        @endif
                                        
                                        @if($review->cons)
                                            <div class="mb-2">
                                                <small class="text-danger"><i class="fas fa-thumbs-down"></i> <strong>Kekurangan:</strong></small>
                                                <p class="mb-1 ms-3">{{ $review->cons }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($reviews->hasPages())
                            <div class="d-flex justify-content-center">
                                {{ $reviews->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-star fa-3x text-muted mb-3"></i>
                            <h5>Belum Ada Ulasan</h5>
                            <p class="text-muted mb-4">
                                Lapangan ini belum memiliki ulasan. Jadilah yang pertama memberikan ulasan!
                            </p>
                            <a href="{{ route('fields.show', $field->id) }}" class="btn btn-primary">
                                <i class="fas fa-eye"></i> Lihat Detail Lapangan
                            </a>
                        </div>
                    @endif
                </div>
                
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('fields.show', $field->id) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Detail Lapangan
                        </a>
                        @auth
                            @php
                                $userCanReview = auth()->user()->bookings()
                                    ->where('field_id', $field->id)
                                    ->whereIn('status', ['completed', 'confirmed'])
                                    ->whereDoesntHave('review')
                                    ->exists();
                            @endphp
                            @if($userCanReview)
                                <a href="{{ route('reviews.reviewable-bookings') }}" class="btn btn-primary">
                                    <i class="fas fa-star"></i> Beri Ulasan
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
