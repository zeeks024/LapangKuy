@extends('layouts.app')

@section('title', 'Edit Booking')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Field Summary -->
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm h-100">
                <div class="position-relative">
                    <img src="{{ $booking->field->image_url }}" class="card-img-top" alt="{{ $booking->field->name }}" style="height: 200px; object-fit: cover;">
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-success">{{ ucfirst($booking->field->category) }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title mb-3">{{ $booking->field->name }}</h5>
                    
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-geo-alt text-primary me-2"></i>
                        <span class="text-muted">{{ $booking->field->location }}</span>
                    </div>
                    
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-clock text-primary me-2"></i>
                        <span class="text-muted">{{ $booking->field->open_time }} - {{ $booking->field->close_time }}</span>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-currency-dollar text-primary me-2"></i>
                        <span class="h6 text-primary mb-0">Rp {{ number_format($booking->field->price, 0, ',', '.') }}/jam</span>
                    </div>
                    
                    <!-- Current Booking Status -->
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Status Booking Saat Ini</h6>
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-{{ $booking->status == 'confirmed' ? 'success' : ($booking->status == 'pending' ? 'warning' : 'danger') }} me-2">
                                    {{ ucfirst($booking->status) }}
                                </span>
                                <small class="text-muted">Booking #{{ $booking->id }}</small>
                            </div>
                            <div class="small">
                                <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}<br>
                                <strong>Waktu:</strong> {{ $booking->start_time }} - {{ $booking->end_time }}<br>
                                <strong>Durasi:</strong> {{ $booking->duration }} jam
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Booking Form -->
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="bi bi-pencil-square me-2"></i>Edit Booking
                    </h4>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($booking->status == 'cancelled')
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Booking ini telah dibatalkan dan tidak dapat diedit.
                        </div>
                    @elseif($booking->status == 'completed')
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            Booking ini telah selesai dan tidak dapat diedit.
                        </div>
                    @else
                        <form method="POST" action="{{ route('bookings.update', $booking) }}" id="editBookingForm">
                            @csrf
                            @method('PUT')
                            
                            <!-- Date Selection -->
                            <div class="mb-4">
                                <label for="date" class="form-label fw-bold">
                                    <i class="bi bi-calendar me-1"></i>Tanggal Booking
                                </label>
                                <input type="date" 
                                       class="form-control @error('date') is-invalid @enderror" 
                                       id="date" 
                                       name="date" 
                                       required 
                                       min="{{ date('Y-m-d') }}" 
                                       max="{{ date('Y-m-d', strtotime('+30 days')) }}"
                                       value="{{ old('date', $booking->date) }}">
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Hanya dapat mengubah tanggal untuk hari ini atau ke depan</div>
                            </div>
                            
                            <!-- Time Selection -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-clock me-1"></i>Waktu Mulai
                                </label>
                                <select class="form-select @error('start_time') is-invalid @enderror" 
                                        name="start_time" 
                                        id="start_time" 
                                        required>
                                    <option value="">Pilih waktu mulai</option>
                                    @foreach($available_slots as $slot)
                                        <option value="{{ $slot }}" 
                                                @if(in_array($slot, $booked_slots) && $slot != $booking->start_time) disabled @endif
                                                @if(old('start_time', $booking->start_time) == $slot) selected @endif>
                                            {{ $slot }}
                                            @if(in_array($slot, $booked_slots) && $slot != $booking->start_time)
                                                (Sudah dibooking)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Duration Selection -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-hourglass-split me-1"></i>Durasi Bermain
                                </label>
                                <select class="form-select @error('duration') is-invalid @enderror" 
                                        name="duration" 
                                        id="duration" 
                                        required>
                                    <option value="">Pilih durasi</option>
                                    @for($i = 1; $i <= 4; $i++)
                                        <option value="{{ $i }}" @if(old('duration', $booking->duration) == $i) selected @endif>
                                            {{ $i }} Jam
                                        </option>
                                    @endfor
                                </select>
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Additional Notes -->
                            <div class="mb-4">
                                <label for="notes" class="form-label fw-bold">
                                    <i class="bi bi-chat-dots me-1"></i>Catatan Tambahan
                                </label>
                                <textarea class="form-control" 
                                          id="notes" 
                                          name="notes" 
                                          rows="3" 
                                          placeholder="Masukkan catatan khusus untuk booking ini (opsional)">{{ old('notes', $booking->notes) }}</textarea>
                                <div class="form-text">Misalnya: jenis permainan, jumlah pemain, dll.</div>
                            </div>
                            
                            <!-- Contact Information -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="contact_name" class="form-label fw-bold">
                                        <i class="bi bi-person me-1"></i>Nama Kontak
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('contact_name') is-invalid @enderror" 
                                           id="contact_name" 
                                           name="contact_name" 
                                           value="{{ old('contact_name', $booking->contact_name) }}" 
                                           required>
                                    @error('contact_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="contact_phone" class="form-label fw-bold">
                                        <i class="bi bi-telephone me-1"></i>Nomor Telepon
                                    </label>
                                    <input type="tel" 
                                           class="form-control @error('contact_phone') is-invalid @enderror" 
                                           id="contact_phone" 
                                           name="contact_phone" 
                                           value="{{ old('contact_phone', $booking->contact_phone) }}" 
                                           required>
                                    @error('contact_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Booking Summary -->
                            <div class="card bg-light mb-4">
                                <div class="card-body">
                                    <h6 class="card-title mb-3">
                                        <i class="bi bi-receipt me-1"></i>Ringkasan Perubahan Booking
                                    </h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Lapangan:</small><br>
                                            <strong>{{ $booking->field->name }}</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Tanggal:</small><br>
                                            <strong id="summary-date">{{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}</strong>
                                        </div>
                                        <div class="col-6 mt-2">
                                            <small class="text-muted">Waktu:</small><br>
                                            <strong id="summary-time">{{ $booking->start_time }}</strong>
                                        </div>
                                        <div class="col-6 mt-2">
                                            <small class="text-muted">Durasi:</small><br>
                                            <strong id="summary-duration">{{ $booking->duration }} jam</strong>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">Total Harga:</span>
                                        <span class="h5 text-primary mb-0" id="totalPrice">
                                            Rp <span id="price-amount">{{ number_format($booking->total_price, 0, ',', '.') }}</span>
                                        </span>
                                    </div>
                                    
                                    @if($booking->total_price != ($booking->field->price * $booking->duration))
                                        <div class="mt-2">
                                            <small class="text-warning">
                                                <i class="bi bi-exclamation-triangle me-1"></i>
                                                Jika ada perubahan, biaya tambahan atau selisih akan dihitung saat pembayaran.
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                                <div>
                                    <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-1"></i>Kembali
                                    </a>
                                </div>
                                <div class="d-flex gap-2">
                                    @if($booking->status == 'pending')
                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                            <i class="bi bi-x-circle me-1"></i>Batalkan
                                        </button>
                                    @endif
                                    <button type="submit" class="btn btn-warning" id="submitBtn">
                                        <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Booking Modal -->
@if($booking->status == 'pending')
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Batalkan Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin membatalkan booking ini?</p>
                <div class="alert alert-warning">
                    <small>
                        <i class="bi bi-info-circle me-1"></i>
                        Pembatalan akan dikenakan biaya sesuai dengan kebijakan yang berlaku.
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                <form method="POST" action="{{ route('bookings.cancel', $booking) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">Ya, Batalkan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('date');
    const startTimeSelect = document.getElementById('start_time');
    const durationSelect = document.getElementById('duration');
    const priceAmount = document.getElementById('price-amount');
    const fieldPrice = {{ $booking->field->price }};
    
    // Update summary
    function updateSummary() {
        const date = dateInput.value;
        const startTime = startTimeSelect.value;
        const duration = durationSelect.value;
        
        // Update summary display
        if (date) {
            document.getElementById('summary-date').textContent = new Date(date).toLocaleDateString('id-ID');
        }
        
        if (startTime) {
            document.getElementById('summary-time').textContent = startTime;
        }
        
        if (duration) {
            document.getElementById('summary-duration').textContent = duration + ' jam';
            // Calculate total price
            const totalPrice = fieldPrice * parseInt(duration);
            priceAmount.textContent = totalPrice.toLocaleString('id-ID');
        }
    }
    
    // Event listeners
    dateInput.addEventListener('change', updateSummary);
    startTimeSelect.addEventListener('change', updateSummary);
    durationSelect.addEventListener('change', updateSummary);
    
    // Form validation
    const editForm = document.getElementById('editBookingForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Memproses...';
        });
    }
});
</script>
@endsection
