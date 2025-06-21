@extends('layouts.app')

@section('title', 'Booking Lapangan')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Field Summary -->
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm h-100">
                <div class="position-relative">
                    <img src="{{ $field->image_url }}" class="card-img-top" alt="{{ $field->name }}" style="height: 200px; object-fit: cover;">
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-success">{{ ucfirst($field->category) }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title mb-3">{{ $field->name }}</h5>
                    
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-geo-alt text-primary me-2"></i>
                        <span class="text-muted">{{ $field->location }}</span>
                    </div>
                    
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-clock text-primary me-2"></i>
                        <span class="text-muted">{{ $field->open_time }} - {{ $field->close_time }}</span>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-currency-dollar text-primary me-2"></i>
                        <span class="h6 text-primary mb-0">Rp {{ number_format($field->price, 0, ',', '.') }}/jam</span>
                    </div>
                      <!-- Facilities -->
                    @if($field->facilities)
                        <h6 class="mb-2">Fasilitas:</h6>
                        <div class="mb-3">
                            @php
                                $facilitiesList = $field->facilities;
                                if (is_string($facilitiesList)) {
                                    $decoded = json_decode($facilitiesList, true);
                                    if (is_array($decoded)) {
                                        $facilitiesList = $decoded;
                                    } else {
                                        $facilitiesList = array_map('trim', explode(',', $facilitiesList));
                                    }
                                }
                            @endphp
                            @foreach($facilitiesList as $facility)
                                <span class="badge bg-light text-dark me-1 mb-1">
                                    <i class="bi bi-check-circle-fill text-success me-1"></i>{{ $facility }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                    <!-- Rating -->
                    @php
                        $averageRating = $field->reviews()->avg('rating') ?? 0;
                        $reviewCount = $field->reviews()->count();
                    @endphp
                    <div class="d-flex align-items-center">
                        <div class="text-warning me-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= round($averageRating) ? '-fill' : '' }}"></i>
                            @endfor
                        </div>
                        <span class="text-muted">({{ number_format($averageRating, 1) }} dari {{ $reviewCount }} ulasan)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Form -->
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-calendar-plus me-2"></i>Form Booking
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

                    <form method="POST" action="{{ route('bookings.store') }}" id="bookingForm">
                        @csrf
                        <input type="hidden" name="field_id" value="{{ $field->id }}">
                        
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
                                   value="{{ old('date', request('date', date('Y-m-d', strtotime('+1 day')))) }}">
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Booking maksimal 30 hari ke depan</div>
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
                                    <option value="{{ $slot }}" @if(old('start_time') == $slot) selected @endif>
                                        {{ $slot }}
                                        @if(in_array($slot, $booked_slots))
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
                                    <option value="{{ $i }}" @if(old('duration') == $i) selected @endif>
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
                                      placeholder="Masukkan catatan khusus untuk booking ini (opsional)">{{ old('notes') }}</textarea>
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
                                       value="{{ old('contact_name', auth()->user()->name ?? '') }}" 
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
                                       value="{{ old('contact_phone') }}" 
                                       required>
                                @error('contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Terms and Conditions -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    Saya setuju dengan <a href="{{ route('terms') }}" target="_blank">syarat dan ketentuan</a> yang berlaku
                                </label>
                            </div>
                        </div>
                        
                        <!-- Booking Summary -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-title mb-3">
                                    <i class="bi bi-receipt me-1"></i>Ringkasan Booking
                                </h6>
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted">Lapangan:</small><br>
                                        <strong id="summary-field">{{ $field->name }}</strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Tanggal:</small><br>
                                        <strong id="summary-date">-</strong>
                                    </div>
                                    <div class="col-6 mt-2">
                                        <small class="text-muted">Waktu:</small><br>
                                        <strong id="summary-time">-</strong>
                                    </div>
                                    <div class="col-6 mt-2">
                                        <small class="text-muted">Durasi:</small><br>
                                        <strong id="summary-duration">-</strong>
                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Total Harga:</span>
                                    <span class="h5 text-primary mb-0" id="totalPrice">
                                        Rp <span id="price-amount">{{ number_format($field->price, 0, ',', '.') }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('fields.show', $field) }}" class="btn btn-outline-secondary me-md-2">
                                <i class="bi bi-arrow-left me-1"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                <i class="bi bi-credit-card me-1"></i>Lanjut ke Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('date');
    const startTimeSelect = document.getElementById('start_time');
    const durationSelect = document.getElementById('duration');
    const priceAmount = document.getElementById('price-amount');
    const fieldPrice = {{ $field->price }};
    
    // Update summary
    function updateSummary() {
        const date = dateInput.value;
        const startTime = startTimeSelect.value;
        const duration = durationSelect.value;
        
        // Update summary display
        document.getElementById('summary-date').textContent = date ? new Date(date).toLocaleDateString('id-ID') : '-';
        document.getElementById('summary-time').textContent = startTime ? startTime : '-';
        document.getElementById('summary-duration').textContent = duration ? duration + ' jam' : '-';
        
        // Calculate total price
        if (duration) {
            const totalPrice = fieldPrice * parseInt(duration);
            priceAmount.textContent = totalPrice.toLocaleString('id-ID');
        } else {
            priceAmount.textContent = fieldPrice.toLocaleString('id-ID');
        }
    }
    
    // Event listeners
    dateInput.addEventListener('change', updateSummary);
    startTimeSelect.addEventListener('change', updateSummary);
    durationSelect.addEventListener('change', updateSummary);
    
    // Initial summary update
    updateSummary();
    
    // Form validation
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Memproses...';
    });
    
    const fieldId = {{ $field->id }};

    dateInput.addEventListener('change', function() {
        const selectedDate = this.value;
        fetch(`/booking/available-slots?field_id=${fieldId}&date=${selectedDate}`)
            .then(response => response.json())
            .then(data => {
                // Kosongkan dropdown
                startTimeSelect.innerHTML = '<option value="">Pilih waktu mulai</option>';
                data.available_slots.forEach(slot => {
                    const option = document.createElement('option');
                    option.value = slot.time;
                    option.textContent = slot.time + (slot.available ? '' : ' (Sudah dibooking)');
                    if (!slot.available) option.disabled = true;
                    startTimeSelect.appendChild(option);
                });
            });
    });
});
</script>
@endsection
