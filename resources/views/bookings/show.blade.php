@extends('layouts.app')
@section('title', 'Detail Booking')
@section('content')
<div class="container fade-in" style="max-width:600px; margin:40px auto;">
    <div class="field-card">
        <div class="field-info">            <div class="field-header">
                @php
                    // Handle both model relationship and flat booking object
                    $fieldName = isset($booking->field) && $booking->field ? $booking->field->name : 
                                ($booking->field_name ?? 'Unknown Field');
                    $fieldLocation = isset($booking->field) && $booking->field ? $booking->field->location : 
                                    ($booking->field_location ?? 'Unknown Location');
                @endphp
                <div class="field-name">{{ $fieldName }}</div>
            </div>
            <div class="field-location"><i class="fas fa-map-marker-alt"></i> {{ $fieldLocation }}</div>            <div style="margin:16px 0;">
                <strong>Tanggal:</strong> {{ $booking->date }}<br>
                <strong>Jam Mulai:</strong> {{ $booking->start_time }}<br>
                <strong>Durasi:</strong> {{ $booking->duration }} jam<br>
                <strong>Status:</strong> {{ $booking->status }}<br>
                <strong>Total Harga:</strong> Rp {{ number_format($booking->total_price, 0, ',', '.') }}
            </div>
              @if($booking->payment_status == 'unpaid' && $booking->status != 'cancelled')
            <div class="mt-4">
                <a href="{{ route('bookings.payment', $booking->id) }}" class="btn btn-primary btn-block w-100 py-2" style="font-weight: 600; font-size: 16px; border-radius: 8px;">
                    <i class="fas fa-credit-card me-2"></i> Lanjut ke Pembayaran
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
