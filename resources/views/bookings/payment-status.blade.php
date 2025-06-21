@extends('layouts.app')

@section('title', 'Status Pembayaran - LapangKuy')

@section('content')
<div class="container py-4 fade-in" style="max-width: 900px;">
    <div class="row justify-content-center mb-4">
        <div class="col-12 col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div id="statusIcon" class="me-3" style="font-size:2.5rem;">
                            <i class="fas fa-spinner fa-spin text-secondary"></i>
                        </div>
                        <div>
                            <h3 class="mb-1" id="statusText">Mengecek Status Pembayaran...</h3>
                            <div class="text-muted" id="statusDescription">Mohon tunggu sebentar, kami sedang memverifikasi status pembayaran Anda</div>
                        </div>
                        <button class="btn btn-outline-secondary ms-auto" onclick="refreshPaymentStatus()" title="Refresh Status">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                    <hr>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-2 small text-muted">Order ID</div>
                            <div class="fw-semibold" id="orderId">LK-{{ $booking->id }}-{{ $booking->created_at->timestamp }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2 small text-muted">Lapangan</div>
                            <div class="fw-semibold" id="fieldName">{{ $booking->field->name ?? 'Loading...' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2 small text-muted">Tanggal & Waktu</div>
                            <div class="fw-semibold" id="bookingDateTime">
                                {{ $booking->date ? \Carbon\Carbon::parse($booking->date)->format('d M Y') : 'Loading...' }}<br>
                                <small>{{ $booking->start_time }} - {{ $booking->end_time }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2 small text-muted">Metode Pembayaran</div>
                            <div class="fw-semibold" id="paymentMethod">{{ $booking->payment_method ?? 'Menunggu...' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2 small text-muted">Total Pembayaran</div>
                            <div class="fw-semibold" id="totalAmount">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-md-6" id="transactionCard" style="display: none;">
                            <div class="mb-2 small text-muted">ID Transaksi</div>
                            <div class="fw-semibold" id="transactionId">-</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 text-center py-3">
                    <a href="{{ route('fields.index') }}" class="btn btn-outline-secondary me-2 mb-2">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Lapangan
                    </a>
                    <a href="{{ route('bookings.index') }}" class="btn btn-primary mb-2">
                        <i class="fas fa-list me-1"></i> Lihat Booking Saya
                    </a>
                    @if($booking->payment_status === 'pending')
                    <button class="btn btn-success mb-2" onclick="showUpdateStatusModal()" id="updateStatusBtn">
                        <i class="fas fa-sync-alt me-1"></i> Update Status Manual
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>
// Payment Status Checker (Bootstrap UI)
class PaymentStatusChecker {
    constructor() {
        this.bookingId = {{ $booking->id }};
        this.maxRetries = 3;
        this.retryCount = 0;
        this.refreshInterval = null;
        this.isRefreshing = false;
        this.elements = {
            statusIcon: document.getElementById('statusIcon'),
            statusText: document.getElementById('statusText'),
            statusDescription: document.getElementById('statusDescription'),
            orderId: document.getElementById('orderId'),
            fieldName: document.getElementById('fieldName'),
            bookingDateTime: document.getElementById('bookingDateTime'),
            paymentMethod: document.getElementById('paymentMethod'),
            totalAmount: document.getElementById('totalAmount'),
            transactionId: document.getElementById('transactionId'),
            transactionCard: document.getElementById('transactionCard')
        };
        this.init();
    }
    init() {
        const params = new URLSearchParams(window.location.search);
        const status = params.get('transaction_status');
        if (status) this.updateStatusFromUrl(status);
        this.checkPaymentStatus();
        // Auto-refresh removed
    }
    updateStatusFromUrl(status) {
        switch (status) {
            case 'settlement':
            case 'capture':
            case 'success':
                this.setStatusDisplay('success', { message: 'Pembayaran berhasil! Booking Anda telah dikonfirmasi.' });
                break;
            case 'pending':
                this.setStatusDisplay('pending', { message: 'Pembayaran sedang diproses. Mohon tunggu konfirmasi.' });
                break;
            case 'deny':
            case 'cancel':
            case 'expire':
            case 'failed':
                this.setStatusDisplay('failed', { message: 'Pembayaran gagal atau dibatalkan.' });
                break;
        }
    }
    async checkPaymentStatus() {
        if (this.isRefreshing) return;
        this.isRefreshing = true;
        this.setLoadingState();
        try {
            const response = await fetch(`{{ route('payment.check-status') }}?booking_id=${this.bookingId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            if (!response.ok) throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            const data = await response.json();
            if (!data.success) throw new Error(data.message || 'API returned error');
            this.updatePaymentStatus(data);
            this.retryCount = 0;
        } catch (error) {
            // Do nothing on error: keep last known status/payment info, no error UI
        } finally {
            this.isRefreshing = false;
        }
    }
    setLoadingState() {
        this.elements.statusIcon.innerHTML = '<i class="fas fa-spinner fa-spin text-secondary"></i>';
        this.elements.statusText.textContent = 'Mengecek Status Pembayaran...';
        this.elements.statusDescription.textContent = 'Mohon tunggu sebentar, kami sedang memverifikasi status pembayaran Anda';
    }
    updatePaymentStatus(data) {
        // Always show payment method from booking if status is settlement/paid/capture/unknown
        let paymentMethod = data.payment_type;
        const fallback = '{{ $booking->payment_method ?? 'Tidak diketahui' }}';
        if (
            !paymentMethod ||
            paymentMethod === 'Unknown' ||
            paymentMethod === 'settlement' ||
            paymentMethod === 'paid' ||
            paymentMethod === 'capture'
        ) {
            paymentMethod = fallback;
        } else {
            paymentMethod = this.formatPaymentMethod(paymentMethod);
        }
        this.elements.paymentMethod.textContent = paymentMethod;

        if (data.field_name && data.field_name !== 'Unknown Field') this.elements.fieldName.textContent = data.field_name;
        if (data.gross_amount && data.gross_amount !== 'N/A') this.elements.totalAmount.textContent = this.formatCurrency(data.gross_amount);
        if (data.transaction_id && data.transaction_id !== 'N/A' && !data.transaction_id.startsWith('TEST-')) {
            this.elements.transactionId.textContent = data.transaction_id;
            this.elements.transactionCard.style.display = 'block';
        }
        const status = data.transaction_status || 'unknown';
        this.setStatusDisplay(status, data);
    }
    setStatusDisplay(status, data) {
        // Remove loading spinner and text after status is set
        switch (status) {
            case 'settlement':
            case 'capture':
            case 'paid':
            case 'success':
                this.elements.statusIcon.innerHTML = '<i class="fas fa-check-circle text-success"></i>';
                this.elements.statusText.textContent = 'Pembayaran Berhasil!';
                this.elements.statusDescription.innerHTML = '<span class="text-success">Pembayaran Anda telah berhasil diproses dan booking dikonfirmasi.</span>';
                break;
            case 'pending':
                this.elements.statusIcon.innerHTML = '<i class="fas fa-clock text-warning"></i>';
                this.elements.statusText.textContent = 'Menunggu Pembayaran';
                this.elements.statusDescription.innerHTML = '<span class="text-warning">Pembayaran Anda sedang diproses. Halaman ini akan otomatis terupdate.</span>';
                break;
            case 'deny':
            case 'cancel':
            case 'expire':
            case 'failed':
            case 'cancelled':
                this.elements.statusIcon.innerHTML = '<i class="fas fa-times-circle text-danger"></i>';
                this.elements.statusText.textContent = 'Pembayaran Gagal';
                this.elements.statusDescription.innerHTML = '<span class="text-danger">Pembayaran gagal atau dibatalkan. Silakan coba lagi atau gunakan metode pembayaran lain.</span>';
                break;
            default:
                this.elements.statusIcon.innerHTML = '<i class="fas fa-question-circle text-secondary"></i>';
                this.elements.statusText.textContent = 'Status Tidak Diketahui';
                this.elements.statusDescription.innerHTML = '<span class="text-muted">Status pembayaran tidak dapat ditentukan. Silakan hubungi customer service.</span>';
        }
    }
    refresh() {
        this.retryCount = 0;
        this.checkPaymentStatus();
    }
    formatPaymentMethod(method) {
        const methods = {
            'credit_card': 'Kartu Kredit',
            'bank_transfer': 'Transfer Bank',
            'gopay': 'GoPay',
            'shopeepay': 'ShopeePay',
            'qris': 'QRIS',
            'manual_update': 'Update Manual'
        };
        return methods[method.toLowerCase()] || method;
    }
    formatCurrency(amount) {
        if (!amount || amount === 'N/A' || amount === 0) return 'N/A';
        const numAmount = typeof amount === 'string' ? parseFloat(amount) : amount;
        if (isNaN(numAmount)) return 'N/A';
        return 'Rp ' + numAmount.toLocaleString('id-ID');
    }
}
document.addEventListener('DOMContentLoaded', function() {
    window.paymentChecker = new PaymentStatusChecker();
});
function refreshPaymentStatus() {
    if (window.paymentChecker) window.paymentChecker.refresh();
}
window.addEventListener('beforeunload', function() {
    if (window.paymentChecker) window.paymentChecker.clearAutoRefresh();
});
</script>
@endsection
