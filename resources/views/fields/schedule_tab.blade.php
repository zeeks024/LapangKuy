<!-- Schedule Tab -->
<div class="tab-pane fade" id="schedule" role="tabpanel">
    <h5 class="mb-4 fw-bold">Jadwal & Ketersediaan</h5>
    
    <div class="alert alert-info mb-4">
        <i class="fas fa-info-circle me-2"></i>
        Berikut jadwal ketersediaan lapangan untuk hari ini dan besok. Silakan klik pada slot waktu yang tersedia untuk melakukan pemesanan.
    </div>

    <!-- Schedule Navigation Tabs -->
    <ul class="nav nav-pills mb-4" id="scheduleNavigation" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="today-tab" data-bs-toggle="pill" 
                    data-bs-target="#today-schedule" type="button" role="tab">
                <i class="fas fa-calendar-day me-2"></i>
                <span class="d-none d-sm-inline">{{ $schedule['today']['day'] }}</span>
                <span>(Hari Ini)</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tomorrow-tab" data-bs-toggle="pill" 
                    data-bs-target="#tomorrow-schedule" type="button" role="tab">
                <i class="fas fa-calendar-plus me-2"></i>
                <span class="d-none d-sm-inline">{{ $schedule['tomorrow']['day'] }}</span>
                <span>(Besok)</span>
            </button>
        </li>
    </ul>
    
    <!-- Schedule Content -->
    <div class="tab-content" id="scheduleContent">
        <!-- Today's Schedule -->
        <div class="tab-pane fade show active" id="today-schedule" role="tabpanel">
            <h6 class="mb-3">
                <i class="fas fa-calendar-day me-2 text-primary"></i>
                Ketersediaan untuk hari {{ $schedule['today']['display_date'] }}
            </h6>
            
            <div class="schedule-legend mb-3 d-flex">
                <div class="me-4 d-flex align-items-center">
                    <div class="status-indicator available me-2"></div>
                    <span>Tersedia</span>
                </div>
                <div class="me-4 d-flex align-items-center">
                    <div class="status-indicator booked me-2"></div>
                    <span>Sudah Dibooking</span>
                </div>
            </div>

            @if(count($schedule['today']['slots']) > 0)
                <div class="row g-3">
                    @foreach($schedule['today']['slots'] as $slot)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="time-slot-card position-relative 
                                    {{ $slot['available'] ? 'available' : 'booked' }}"
                                 data-time="{{ $slot['time'] }}">
                                <div class="time">
                                    <i class="fas fa-clock me-2"></i>{{ $slot['time'] }}
                                </div>
                                <div class="price">Rp{{ number_format($slot['price'],0,',','.') }}</div>
                                
                                @if($slot['available'])
                                    <a href="{{ route('bookings.create', ['field_id' => $field->id, 'date' => $schedule['today']['date'], 'time' => $slot['time']]) }}" 
                                       class="btn-book">
                                        <i class="fas fa-check-circle me-1"></i> Booking
                                    </a>
                                @else
                                    <div class="status-badge booked">
                                        <i class="fas fa-times-circle me-1"></i> Sudah Dibooking
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Tidak ada slot waktu yang tersedia untuk hari ini.
                </div>
            @endif
        </div>
        
        <!-- Tomorrow's Schedule -->
        <div class="tab-pane fade" id="tomorrow-schedule" role="tabpanel">
            <h6 class="mb-3">
                <i class="fas fa-calendar-plus me-2 text-success"></i>
                Ketersediaan untuk hari {{ $schedule['tomorrow']['display_date'] }}
            </h6>
            
            <div class="schedule-legend mb-3 d-flex">
                <div class="me-4 d-flex align-items-center">
                    <div class="status-indicator available me-2"></div>
                    <span>Tersedia</span>
                </div>
                <div class="me-4 d-flex align-items-center">
                    <div class="status-indicator booked me-2"></div>
                    <span>Sudah Dibooking</span>
                </div>
            </div>

            @if(count($schedule['tomorrow']['slots']) > 0)
                <div class="row g-3">
                    @foreach($schedule['tomorrow']['slots'] as $slot)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="time-slot-card position-relative 
                                    {{ $slot['available'] ? 'available' : 'booked' }}"
                                 data-time="{{ $slot['time'] }}">
                                <div class="time">
                                    <i class="fas fa-clock me-2"></i>{{ $slot['time'] }}
                                </div>
                                <div class="price">Rp{{ number_format($slot['price'],0,',','.') }}</div>
                                
                                @if($slot['available'])
                                    <a href="{{ route('bookings.create', ['field_id' => $field->id, 'date' => $schedule['tomorrow']['date'], 'time' => $slot['time']]) }}" 
                                       class="btn-book">
                                        <i class="fas fa-check-circle me-1"></i> Booking
                                    </a>
                                @else
                                    <div class="status-badge booked">
                                        <i class="fas fa-times-circle me-1"></i> Sudah Dibooking
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Tidak ada slot waktu yang tersedia untuk besok.
                </div>
            @endif
        </div>
    </div>
</div>
