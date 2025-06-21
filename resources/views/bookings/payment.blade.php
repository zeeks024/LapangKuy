@extends('layouts.app')

@section('title', 'Pembayaran Booking')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Pembayaran Booking</h4>
                </div>
                <div class="card-body">
                    <!-- Booking Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Detail Booking</h6>
                            <table class="table table-sm table-borderless">                                <tr>
                                    <td>Kode Booking:</td>
                                    <td><strong>#{{ $booking->booking_code ?? $booking->id }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Lapangan:</td>
                                    <td><strong>
                                    @php
                                        $fieldName = isset($booking->field) && $booking->field ? $booking->field->name : 
                                                    ($booking->field_name ?? 'Unknown Field');
                                    @endphp
                                    {{ $fieldName }}
                                    </strong></td>
                                </tr>                                <tr>
                                    <td>Tanggal:</td>
                                    <td><strong>{{ $booking->date ? \Carbon\Carbon::parse($booking->date)->format('d/m/Y') : '-' }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Waktu:</td>
                                    <td><strong>{{ $booking->start_time }} - {{ $booking->end_time }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Durasi:</td>
                                    <td><strong>{{ $booking->duration }} jam</strong></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded">
                                <h6 class="fw-bold">Total Pembayaran</h6>
                                <div class="d-flex justify-content-between">
                                    <span>Harga Lapangan ({{ $booking->duration }} jam):</span>
                                    <span>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total:</span>
                                    <span class="text-primary fs-5">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Status -->
                    @if($booking->payment_status === 'pending')
                        <div class="alert alert-warning">
                            <i class="fas fa-clock me-2"></i>
                            Pembayaran sedang menunggu konfirmasi. Silakan lanjutkan proses pembayaran.
                        </div>
                    @elseif($booking->payment_status === 'paid')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            Pembayaran telah berhasil! Terima kasih atas pembayaran Anda.
                        </div>
                    @elseif($booking->payment_status === 'failed')
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle me-2"></i>
                            Pembayaran gagal. Silakan coba lagi.
                        </div>
                    @endif

                    <!-- Payment Methods Info -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Metode Pembayaran Yang Tersedia</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center p-3">
                                        <i class="fas fa-credit-card fa-2x mb-2 text-primary"></i>
                                        <h6>Kartu Kredit/Debit</h6>
                                        <small class="text-muted">Visa, Mastercard, JCB</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center p-3">
                                        <i class="fas fa-university fa-2x mb-2 text-success"></i>
                                        <h6>Transfer Bank</h6>
                                        <small class="text-muted">BCA, Mandiri, BNI, BRI</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center p-3">
                                        <i class="fas fa-mobile-alt fa-2x mb-2 text-warning"></i>
                                        <h6>E-Wallet</h6>
                                        <small class="text-muted">GoPay, OVO, DANA, LinkAja</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                    <!-- Payment Button -->
                    <input type="hidden" id="booking-id" value="{{ $booking->id }}">
                    <div id="payment-error" class="alert alert-danger mb-3" style="display: none;"></div>
                    
                    @if($booking->payment_status !== 'paid')
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary btn-lg" id="pay-button"
                                   data-token="{{ $snapToken }}"
                                   data-client-key="{{ config('midtrans.client_key') }}"
                                   data-test-mode="{{ empty(config('midtrans.server_key')) || config('midtrans.server_key') === 'SB-Mid-server-xxxxxxxxxxxxxxxx' ? 'true' : 'false' }}">
                                <i class="fas fa-lock me-2"></i>
                                Bayar Sekarang - Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </button>                            <p class="text-center text-muted mt-2 small">Pembayaran melalui Midtrans - Aman & Terpercaya</p>
                            <a href="{{ route('bookings.payment.status', $booking) }}" class="btn btn-outline-info">
                                <i class="fas fa-search me-2"></i>Cek Status Pembayaran
                            </a>
                            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline-secondary">
                                Kembali ke Detail Booking
                            </a>
                        </div>
                    @else
                        <div class="d-grid gap-2">
                            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-success btn-lg">
                                <i class="fas fa-check me-2"></i>
                                Lihat Detail Booking
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Midtrans Snap -->
@if(empty(config('midtrans.server_key')) || config('midtrans.server_key') === 'SB-Mid-server-xxxxxxxxxxxxxxxx')
    <!-- Mode Testing - Show testing payment UI -->
    <div class="modal fade" id="testPaymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Test Payment</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Mode Testing:</strong> Ini adalah simulasi pembayaran untuk tujuan testing.
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Pembayaran</label>
                        <div class="form-control-static">
                            <strong>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status Pembayaran</label>
                        <select class="form-select" id="test-payment-status">
                            <option value="success">Berhasil</option>
                            <option value="pending">Tertunda</option>
                            <option value="failed">Gagal</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="test-pay-button">Proses Pembayaran</button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.getElementById('pay-button').onclick = function(){
            // Tampilkan modal testing payment
            var testPaymentModal = new bootstrap.Modal(document.getElementById('testPaymentModal'));
            testPaymentModal.show();
        };
          document.getElementById('test-pay-button').onclick = function(){
            var status = document.getElementById('test-payment-status').value;
            
            if (status === 'success') {
                window.location.href = '{{ route('bookings.payment.status', $booking) }}?transaction_status=settlement&order_id=LK-{{ $booking->id }}-test';
            } else if (status === 'pending') {
                window.location.href = '{{ route('bookings.payment.status', $booking) }}?transaction_status=pending&order_id=LK-{{ $booking->id }}-test';
            } else {
                window.location.href = '{{ route('bookings.payment.status', $booking) }}?transaction_status=failed&order_id=LK-{{ $booking->id }}-test';
            }
        };
    </script>
@else
    <!-- Mode Produksi/Sandbox - Use Midtrans -->
    @if(config('midtrans.is_production'))
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @else
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endif    <script type="text/javascript">
    // Enhanced Payment Manager inspired by SiTumbuh project
    class PaymentManager {
        constructor() {
            this.isPaymentInProgress = false;
            this.currentSnapToken = null;
            this.maxRetries = 3;
            this.retryCount = 0;
            this.payButton = document.getElementById('pay-button');
            this.bookingId = {{ $booking->id }};
            
            this.init();
        }
        
        init() {
            if (this.payButton) {
                this.payButton.addEventListener('click', (e) => this.handlePaymentClick(e));
            }
        }
        
        async handlePaymentClick(e) {
            e.preventDefault();
            
            // Prevent multiple clicks - critical for fixing the "state transition" error
            if (this.isPaymentInProgress) {
                console.log('Payment already in progress, ignoring click...');
                return;
            }
            
            // Check if Snap is available
            if (typeof snap === 'undefined') {
                this.showError('Midtrans Snap belum dimuat. Mohon refresh halaman.');
                return;
            }
            
            this.startPayment();
        }
        
        async startPayment() {
            try {
                this.isPaymentInProgress = true;
                this.setLoadingState();
                this.clearExistingAlerts();
                
                // Get fresh token from server
                const tokenData = await this.requestPaymentToken();
                
                if (tokenData.success && tokenData.snap_token) {
                    this.currentSnapToken = tokenData.snap_token;
                    this.openSnapPayment();
                } else {
                    throw new Error(tokenData.message || 'Gagal mendapatkan token pembayaran');
                }
                
            } catch (error) {
                console.error('Payment initialization error:', error);
                this.handlePaymentError(error.message);
            }
        }
        
        async requestPaymentToken() {
            const response = await fetch('{{ route('bookings.payment.process', $booking) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    booking_id: this.bookingId
                })
            });
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            return await response.json();
        }
        
        openSnapPayment() {
            // Critical: Clear any existing Snap state before opening new one
            if (window.snap && window.snap.hide) {
                window.snap.hide();
            }
            
            console.log('Opening Snap payment with token:', this.currentSnapToken.substring(0, 10) + '...');
            
            // Use setTimeout to ensure proper state management
            setTimeout(() => {
                snap.pay(this.currentSnapToken, {
                    onSuccess: (result) => this.handlePaymentSuccess(result),
                    onPending: (result) => this.handlePaymentPending(result),
                    onError: (result) => this.handlePaymentError(result.status_message || 'Pembayaran gagal'),
                    onClose: () => this.handlePaymentClose()
                });
            }, 100);
        }
        
        handlePaymentSuccess(result) {
            console.log('Payment success:', result);
            this.resetPaymentState();
            
            this.showSuccess('Pembayaran berhasil! Mengalihkan ke halaman status...');
            
            setTimeout(() => {
                window.location.href = `{{ route('bookings.payment.status', $booking) }}?transaction_status=settlement&order_id=${result.order_id}`;
            }, 1500);
        }
        
        handlePaymentPending(result) {
            console.log('Payment pending:', result);
            this.resetPaymentState();
            
            this.showWarning('Pembayaran sedang diproses. Mengalihkan ke halaman status...');
            
            setTimeout(() => {
                window.location.href = `{{ route('bookings.payment.status', $booking) }}?transaction_status=pending&order_id=${result.order_id}`;
            }, 1500);
        }
        
        handlePaymentError(message) {
            console.error('Payment error:', message);
            this.resetPaymentState();
            this.showError(`Pembayaran gagal: ${message}`);
        }
        
        handlePaymentClose() {
            console.log('Payment popup closed by user');
            this.resetPaymentState();
            this.showWarning('Pembayaran dibatalkan. Silakan coba lagi jika diperlukan.');
        }
        
        setLoadingState() {
            if (this.payButton) {
                this.payButton.disabled = true;
                this.payButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses Pembayaran...';
            }
            
            this.showInfo('Mempersiapkan pembayaran...');
        }
        
        resetPaymentState() {
            this.isPaymentInProgress = false;
            this.currentSnapToken = null;
            
            if (this.payButton) {
                this.payButton.disabled = false;
                this.payButton.innerHTML = '<i class="fas fa-lock me-2"></i>Bayar Sekarang - Rp {{ number_format($booking->total_price, 0, ',', '.') }}';
            }
        }
        
        clearExistingAlerts() {
            const alerts = document.querySelectorAll('.alert-info, .alert-danger, .alert-success, .alert-warning');
            alerts.forEach(alert => {
                // Don't remove the permanent payment status alert
                if (!alert.textContent.includes('Pembayaran sedang menunggu konfirmasi')) {
                    alert.remove();
                }
            });
        }
        
        showAlert(type, message) {
            this.clearExistingAlerts();
            
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} mt-3`;
            alertDiv.innerHTML = `<i class="fas fa-${this.getAlertIcon(type)} me-2"></i>${message}`;
            
            document.querySelector('.card-body').appendChild(alertDiv);
            
            // Auto-hide info alerts after 5 seconds
            if (type === 'info') {
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.remove();
                    }
                }, 5000);
            }
        }
        
        showSuccess(message) { this.showAlert('success', message); }
        showError(message) { this.showAlert('danger', message); }
        showWarning(message) { this.showAlert('warning', message); }
        showInfo(message) { this.showAlert('info', message); }
        
        getAlertIcon(type) {
            const icons = {
                'success': 'check-circle',
                'danger': 'exclamation-triangle',
                'warning': 'info-circle',
                'info': 'spinner fa-spin'
            };
            return icons[type] || 'info-circle';
        }
    }
    
    // Initialize payment manager when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        window.paymentManager = new PaymentManager();
        
        // Debug information
        console.log('Payment Manager initialized:', {
            environment: '{{ config('midtrans.is_production') ? 'PRODUCTION' : 'SANDBOX' }}',
            clientKey: '{{ config('midtrans.client_key') }}',
            snapLoaded: typeof snap !== 'undefined',
            bookingId: {{ $booking->id }}
        });
    });
    
    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        if (window.paymentManager) {
            window.paymentManager.resetPaymentState();
        }
    });
    </script>
@endif

<style>
.card {
    border: none;
    border-radius: 10px;
}

.bg-light {
    background-color: #f8f9fa !important;
}

.btn-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border: none;
    border-radius: 8px;
    font-weight: 600;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #0056b3, #004085);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.alert {
    border-radius: 8px;
}
</style>
@endsection
