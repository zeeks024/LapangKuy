@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-star"></i> Booking yang Dapat Diulas</h4>
                    <p class="mb-0 text-muted">Berikan ulasan untuk booking yang telah Anda selesaikan</p>
                </div>                <div class="card-body">
                    @if(isset($reviewableBookings) && $reviewableBookings && $reviewableBookings->count() > 0)
                        <div class="row">
                            @foreach($reviewableBookings as $booking)
                                <div class="col-md-6 mb-4">
                                    <div class="card border">
                                        <div class="card-body">                                            <div class="row">
                                                <div class="col-4">
                                                    @if($booking->field && $booking->field->image_url)
                                                        <img src="{{ $booking->field->image_url }}" 
                                                             class="img-fluid rounded" alt="{{ $booking->field->name }}">
                                                    @else
                                                        <div class="bg-light p-3 rounded text-center">
                                                            <i class="fas fa-image fa-2x text-muted"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-8">
                                                    <h6 class="card-title">{{ $booking->field->name }}</h6>
                                                    <p class="card-text">
                                                        <small class="text-muted">
                                                            <i class="fas fa-map-marker-alt"></i> {{ $booking->field->location }}
                                                        </small>
                                                    </p>
                                                    <p class="card-text">
                                                        <strong>Kode:</strong> {{ $booking->booking_code }}<br>
                                                        <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($booking->date)->format('d/m/Y') }}<br>
                                                        <strong>Waktu:</strong> {{ $booking->start_time }} - {{ $booking->end_time }}
                                                    </p>
                                                    
                                                    @if($booking->status === 'completed')
                                                        <span class="badge bg-success mb-2">
                                                            <i class="fas fa-check"></i> Selesai
                                                        </span>
                                                    @elseif($booking->status === 'confirmed')
                                                        <span class="badge bg-primary mb-2">
                                                            <i class="fas fa-clock"></i> Dikonfirmasi
                                                        </span>
                                                    @endif
                                                    
                                                    <div class="d-grid">
                                                        <a href="{{ route('reviews.create-from-booking', $booking->id) }}" 
                                                           class="btn btn-primary btn-sm">
                                                            <i class="fas fa-star"></i> Beri Ulasan
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($reviewableBookings->hasPages())
                            <div class="d-flex justify-content-center">
                                {{ $reviewableBookings->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-star fa-3x text-muted mb-3"></i>
                            <h5>Belum Ada Booking yang Dapat Diulas</h5>
                            <p class="text-muted mb-4">
                                Anda belum memiliki booking yang selesai dan dapat diulas. 
                                Setelah menyelesaikan booking, Anda dapat memberikan ulasan di sini.
                            </p>
                            <a href="{{ route('fields.index') }}" class="btn btn-primary">
                                <i class="fas fa-search"></i> Cari Lapangan
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Help Section -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6><i class="fas fa-info-circle"></i> Informasi</h6>
                    <p class="mb-0 small text-muted">
                        Anda dapat memberikan ulasan untuk booking yang sudah selesai atau dikonfirmasi dan belum pernah diulas sebelumnya. 
                        Ulasan Anda akan membantu pengguna lain dalam memilih lapangan yang tepat.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
