@extends('layouts.app')
@section('title', 'Status Pembayaran - LapangKuy')

@push('styles')
<style>
    /* Payment Status Specific Styles */
    .status-badge {
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.5rem 1.2rem;
        border-radius: 30px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .status-badge.pending {
        background-color: rgba(245, 158, 11, 0.15);
        color: #92400e;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }
    
    .status-badge.success {
        background-color: rgba(16, 185, 129, 0.15);
        color: #065f46;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    
    .status-badge.failed {
        background-color: rgba(239, 68, 68, 0.15);
        color: #b91c1c;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    
    .status-badge.loading {
        background-color: rgba(59, 130, 246, 0.15);
        color: #1e40af;
        border: 1px solid rgba(59, 130, 246, 0.3);
    }
    
    .status-card {
        transition: all 0.3s ease;
    }
    
    .payment-details-card {
        border-radius: 0.75rem;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .payment-details-card .card-header {
        background: linear-gradient(45deg, #043E03, #0A5C08);
        color: white;
        padding: 1.25rem;
        font-weight: 600;
        border-bottom: none;
    }
    
    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .detail-row:last-child {
        border-bottom: none;
    }
    
    .detail-label {
        color: #6c757d;
        font-weight: 500;
        font-size: 0.95rem;
    }
    
    .detail-value {
        font-weight: 600;
        color: #2c3e50;
        text-align: right;
    }
    
    .refresh-btn {
        border: none;
        background: none;
        color: white;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .refresh-btn:hover {
        background-color: rgba(255, 255, 255, 0.2);
        transform: rotate(15deg);
    }
    
    .refresh-btn:active {
        transform: rotate(180deg);
    }
    
    .status-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
    
    .payment-success .status-icon {
        color: #10b981;
    }
    
    .payment-pending .status-icon {
        color: #f59e0b;
    }
    
    .payment-failed .status-icon {
        color: #ef4444;
    }
    
    .payment-loading .status-icon {
        color: #3b82f6;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .loading-spinner {
        animation: spin 1s linear infinite;
        display: inline-block;
    }
    
    /* Confetti Animation */
    .confetti-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 10000;
    }
    
    .confetti {
        position: absolute;
        width: 10px;
        height: 10px;
        opacity: 0;
    }
    
    @keyframes confetti-fall {
        0% {
            transform: translateY(-100px) rotate(0deg);
            opacity: 1;
        }
        100% {
            transform: translateY(100vh) rotate(360deg);
            opacity: 0;
        }
    }
    
    /* Notification */
    .status-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        color: white;
        max-width: 300px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        animation: slide-in 0.3s ease-out;
    }
    
    @keyframes slide-in {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    .status-notification.info {
        background-color: #3b82f6;
    }
    
    .status-notification.success {
        background-color: #10b981;
    }
    
    .status-notification.warning {
        background-color: #f59e0b;
    }
    
    /* Connection Status */
    .connection-status {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        position: absolute;
        top: 15px;
        right: 15px;
    }
    
    .connection-status.connected {
        background-color: rgba(16, 185, 129, 0.15);
        color: #065f46;
    }
    
    .connection-status.reconnecting {
        background-color: rgba(245, 158, 11, 0.15);
        color: #92400e;
        animation: pulse 1.5s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }
    
    .connection-status.connected .status-dot {
        background-color: #10b981;
    }
    
    .connection-status.reconnecting .status-dot {
        background-color: #f59e0b;
    }
</style>
@endpush

@section('content')
<div class="container py-4 fade-in">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('bookings.index') }}">Booking Saya</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Status Pembayaran</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="section-title mb-0">Status Pembayaran</h1>
                <div>
                    <button class="btn btn-sm btn-outline-primary" onclick="refreshPaymentStatus()">
                        <i class="fas fa-sync-alt me-2"></i>Refresh
                    </button>
                </div>
            </div>
            <p class="text-muted">Monitoring pembayaran booking lapangan LapangKuy Anda</p>
        </div>
    </div>

    <!-- Main Status -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card status-card border-0 shadow-sm">
                <div class="card-body text-center py-5" id="paymentStatus">
                    <div class="loading-state">
                        <div class="status-icon loading-spinner">
                            <i class="fas fa-circle-notch"></i>
                        </div>
                        <h3 class="mb-3">Mengecek Status Pembayaran</h3>
                        <p class="text-muted mb-4" id="statusDescription">
                            Mohon tunggu sebentar, kami sedang memverifikasi status pembayaran Anda...
                        </p>
                        <div class="status-badge loading">
                            <i class="fas fa-spinner fa-spin"></i>
                            <span>Memuat</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Details -->
    <div class="row mb-4">
        <div class="col-md-7">
            <div class="payment-details-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Detail Pemesanan</span>
                    <div>
                        <span class="badge bg-primary">ID: {{ $booking->booking_code ?? 'LK'.strtoupper(substr(md5($booking->id), 0, 8)) }}</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-futbol me-2 text-primary"></i>
                            Lapangan
                        </div>
                        <div class="detail-value" id="fieldName">{{ $booking->field->name ?? 'Loading...' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-calendar-alt me-2 text-primary"></i>
                            Tanggal Booking
                        </div>
                        <div class="detail-value" id="bookingDate">
                            {{ $booking->date ? \Carbon\Carbon::parse($booking->date)->format('d M Y') : 'Loading...' }}
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-clock me-2 text-primary"></i>
                            Waktu
                        </div>
                        <div class="detail-value" id="bookingTime">{{ $booking->start_time }} - {{ $booking->end_time }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-hourglass-half me-2 text-primary"></i>
                            Durasi
                        </div>
                        <div class="detail-value">{{ $booking->duration }} jam</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="payment-details-card">
                <div class="card-header">
                    <span>Detail Pembayaran</span>
                </div>
                <div class="card-body p-4">
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-credit-card me-2 text-primary"></i>
                            Metode Pembayaran
                        </div>
                        <div class="detail-value" id="paymentMethod">{{ $booking->payment_method ?? 'Menunggu...' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-money-bill-wave me-2 text-primary"></i>
                            Total Pembayaran
                        </div>
                        <div class="detail-value" id="totalAmount">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                    </div>
                    <div class="detail-row" id="transactionRow" style="display: none;">
                        <div class="detail-label">
                            <i class="fas fa-receipt me-2 text-primary"></i>
                            ID Transaksi
                        </div>
                        <div class="detail-value" id="transactionId">-</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-calendar-check me-2 text-primary"></i>
                            Tanggal Pembayaran
                        </div>
                        <div class="detail-value" id="paymentDate">
                            {{ $booking->paid_at ? \Carbon\Carbon::parse($booking->paid_at)->format('d M Y H:i') : '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-between gap-3">
                        <a href="{{ route('fields.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Kembali ke Lapangan
                        </a>
                        <a href="{{ route('bookings.index') }}" class="btn btn-primary">
                            <i class="fas fa-list me-2"></i>
                            Lihat Booking Saya
                        </a>
                        
                        @if($booking->payment_status === 'pending')
                        <button class="btn btn-success" onclick="showUpdateStatusModal()" id="updateStatusBtn">
                            <i class="fas fa-sync-alt me-2"></i>
                            Update Status Manual
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($booking->receipt_url) && $booking->receipt_url)
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiptModalLabel">Bukti Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ asset($booking->receipt_url) }}" class="img-fluid" alt="Bukti Pembayaran">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="{{ asset($booking->receipt_url) }}" download="bukti_pembayaran_{{ $booking->booking_code }}.jpg" class="btn btn-primary">Download</a>
            </div>
        </div>
    </div>
</div>
@endif

<script>
class PaymentStatusChecker {
    constructor() {
        this.bookingId = {{ $booking->id }};
        this.maxRetries = 3;
        this.retryCount = 0;
        this.refreshInterval = null;
        this.isRefreshing = false;
        this.init();
    }

    init() {
        const params = new URLSearchParams(window.location.search);
        const status = params.get('transaction_status');

        if (status) {
            this.updateStatusFromUrl(status);
        }

        this.checkPaymentStatus();
        this.setupAutoRefresh();
    }

    updateStatusFromUrl(status) {
        switch (status) {
            case 'settlement':
            case 'capture':
            case 'success':
                this.renderSuccessState('Pembayaran berhasil! Booking Anda telah dikonfirmasi.');
                break;
            case 'pending':
                this.renderPendingState('Pembayaran sedang diproses. Mohon tunggu konfirmasi.');
                break;
            case 'deny':
            case 'cancel':
            case 'expire':
            case 'failed':
                this.renderFailedState('Pembayaran gagal atau dibatalkan.');
                break;
        }
    }

    async checkPaymentStatus() {
        if (this.isRefreshing) return;
        
        this.isRefreshing = true;
        this.renderLoadingState();

        try {
            const response = await fetch(`{{ route("payment.check-status") }}?booking_id=${this.bookingId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.message || 'API returned error');
            }

            this.updatePaymentStatus(data);
            this.retryCount = 0;
            this.showConnectionStatus('connected');

        } catch (error) {
            console.error('Error checking payment status:', error);
            this.handleError(error);
        } finally {
            this.isRefreshing = false;
        }
    }

    updatePaymentStatus(data) {
        try {
            // Update UI with received data
            if (data.field_name && data.field_name !== 'Unknown Field') {
                document.getElementById('fieldName').textContent = data.field_name;
            }

            if (data.payment_type && data.payment_type !== 'Unknown') {
                document.getElementById('paymentMethod').textContent = this.formatPaymentMethod(data.payment_type);
            }

            if (data.gross_amount && data.gross_amount !== 'N/A') {
                document.getElementById('totalAmount').textContent = this.formatCurrency(data.gross_amount);
            }

            if (data.transaction_time) {
                document.getElementById('paymentDate').textContent = this.formatDate(data.transaction_time);
            }

            if (data.transaction_id && data.transaction_id !== 'N/A' && !data.transaction_id.startsWith('TEST-')) {
                document.getElementById('transactionId').textContent = data.transaction_id;
                document.getElementById('transactionRow').style.display = 'flex';
            }

            const status = data.transaction_status || 'unknown';
            
            switch (status) {
                case 'settlement':
                case 'capture':
                case 'paid':
                case 'success':
                    this.renderSuccessState('Pembayaran berhasil! Booking Anda telah dikonfirmasi.');
                    this.triggerSuccessCelebration();
                    this.clearAutoRefresh();
                    this.showNotification('🎉 Pembayaran berhasil dikonfirmasi!', 'success');
                    
                    const updateBtn = document.getElementById('updateStatusBtn');
                    if (updateBtn) {
                        updateBtn.style.display = 'none';
                    }
                    break;
                
                case 'pending':
                    this.renderPendingState('Pembayaran sedang diproses.');
                    this.setupAutoRefresh();
                    this.showNotification('💫 Status akan diperbarui secara otomatis', 'info');
                    break;
                
                case 'deny':
                case 'cancel':
                case 'expire':
                case 'failed':
                case 'cancelled':
                    this.renderFailedState(data.message || 'Pembayaran tidak dapat diproses.');
                    this.clearAutoRefresh();
                    break;
                
                default:
                    this.renderUnknownState(data.message || 'Status pembayaran tidak dapat ditentukan.');
                    this.setupAutoRefresh();
            }

        } catch (error) {
            console.error('Error updating payment status UI:', error);
            this.renderUnknownState('Memperbarui tampilan...');
        }
    }

    renderLoadingState() {
        const statusContainer = document.getElementById('paymentStatus');
        statusContainer.innerHTML = `
            <div class="loading-state">
                <div class="status-icon loading-spinner">
                    <i class="fas fa-circle-notch"></i>
                </div>
                <h3 class="mb-3">Mengecek Status Pembayaran</h3>
                <p class="text-muted mb-4">
                    Mohon tunggu sebentar, kami sedang memverifikasi status pembayaran Anda...
                </p>
                <div class="status-badge loading">
                    <i class="fas fa-spinner fa-spin"></i>
                    <span>Memuat</span>
                </div>
            </div>
        `;
    }

    renderSuccessState(message) {
        const statusContainer = document.getElementById('paymentStatus');
        statusContainer.innerHTML = `
            <div class="payment-success">
                <div class="status-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 class="mb-3">Pembayaran Berhasil!</h3>
                <p class="text-muted mb-4">
                    🎉 Selamat! Pembayaran Anda telah berhasil diproses.<br>
                    Booking lapangan Anda sudah dikonfirmasi dan siap digunakan.
                </p>
                <div class="status-badge success">
                    <i class="fas fa-check-circle"></i>
                    <span>Sukses</span>
                </div>
            </div>
        `;
    }

    renderPendingState(message) {
        const statusContainer = document.getElementById('paymentStatus');
        statusContainer.innerHTML = `
            <div class="payment-pending">
                <div class="status-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3 class="mb-3">Menunggu Pembayaran</h3>
                <p class="text-muted mb-4">
                    ⏳ Pembayaran Anda sedang diproses.<br>
                    Halaman ini akan otomatis terupdate ketika pembayaran berhasil.
                </p>
                <div class="status-badge pending">
                    <i class="fas fa-clock"></i>
                    <span>Pending</span>
                </div>
            </div>
        `;
    }

    renderFailedState(message) {
        const statusContainer = document.getElementById('paymentStatus');
        statusContainer.innerHTML = `
            <div class="payment-failed">
                <div class="status-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <h3 class="mb-3">Pembayaran Gagal</h3>
                <p class="text-muted mb-4">
                    ${message}<br>
                    Silakan coba lagi atau gunakan metode pembayaran lain.
                </p>
                <div class="status-badge failed">
                    <i class="fas fa-times-circle"></i>
                    <span>Gagal</span>
                </div>
            </div>
        `;
    }

    renderUnknownState(message) {
        const statusContainer = document.getElementById('paymentStatus');
        statusContainer.innerHTML = `
            <div>
                <div class="status-icon">
                    <i class="fas fa-sync-alt"></i>
                </div>
                <h3 class="mb-3">Memperbarui Status</h3>
                <p class="text-muted mb-4">
                    ${message}<br>
                    Halaman ini akan otomatis diperbarui ketika informasi tersedia.
                </p>
                <div class="status-badge loading">
                    <i class="fas fa-sync-alt"></i>
                    <span>Memperbarui</span>
                </div>
            </div>
        `;
    }

    handleError(error) {
        this.retryCount++;
        this.showConnectionStatus('reconnecting');
        
        if (this.retryCount < this.maxRetries) {
            const statusContainer = document.getElementById('paymentStatus');
            statusContainer.innerHTML = `
                <div>
                    <div class="status-icon loading-spinner">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <h3 class="mb-3">Memperbarui Status</h3>
                    <p class="text-muted mb-4">
                        Mencoba terhubung kembali... (${this.retryCount}/${this.maxRetries})<br>
                        Mohon tunggu sebentar.
                    </p>
                    <div class="status-badge loading">
                        <i class="fas fa-sync-alt fa-spin"></i>
                        <span>Mencoba ulang</span>
                    </div>
                </div>
            `;
            
            setTimeout(() => {
                this.checkPaymentStatus();
            }, 2000 * this.retryCount);
        } else {
            const statusContainer = document.getElementById('paymentStatus');
            statusContainer.innerHTML = `
                <div>
                    <div class="status-icon">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <h3 class="mb-3">Memperbarui Status</h3>
                    <p class="text-muted mb-4">
                        Sistem sedang memperbarui status pembayaran Anda<br>
                        Halaman akan terus mencoba memperbarui secara otomatis.
                    </p>
                    <button class="btn btn-outline-primary" onclick="refreshPaymentStatus()">
                        <i class="fas fa-sync-alt me-2"></i>Coba Lagi
                    </button>
                </div>
            `;
            
            setTimeout(() => {
                this.retryCount = 0;
                this.checkPaymentStatus();
            }, 10000);
        }
    }

    showConnectionStatus(status) {
        let indicator = document.querySelector('.connection-status');
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.className = 'connection-status';
            document.querySelector('.card.status-card').appendChild(indicator);
        }
        
        indicator.className = `connection-status ${status}`;
        
        switch (status) {
            case 'connected':
                indicator.innerHTML = '<span class="status-dot"></span> Terhubung';
                setTimeout(() => {
                    if (indicator.parentNode) {
                        indicator.remove();
                    }
                }, 3000);
                break;
            case 'reconnecting':
                indicator.innerHTML = '<span class="status-dot"></span> Memperbarui...';
                break;
        }
    }

    showNotification(message, type = 'info') {
        const existingNotification = document.querySelector('.status-notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        const notification = document.createElement('div');
        notification.className = `status-notification ${type}`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => notification.remove(), 300);
        }, 4000);
    }

    triggerSuccessCelebration() {
        const confettiContainer = document.createElement('div');
        confettiContainer.className = 'confetti-container';
        document.body.appendChild(confettiContainer);

        const colors = ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6'];

        for (let i = 0; i < 100; i++) {
            const confetti = document.createElement('div');
            confetti.className = 'confetti';
            confetti.style.left = Math.random() * 100 + '%';
            confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            confetti.style.width = Math.random() * 10 + 5 + 'px';
            confetti.style.height = Math.random() * 10 + 5 + 'px';
            confetti.style.animation = `confetti-fall ${Math.random() * 3 + 2}s linear forwards`;
            confetti.style.animationDelay = Math.random() * 3 + 's';
            
            confettiContainer.appendChild(confetti);
        }
        
        setTimeout(() => {
            if (confettiContainer.parentNode) {
                confettiContainer.remove();
            }
        }, 6000);
    }

    setupAutoRefresh() {
        this.clearAutoRefresh();
        
        this.refreshInterval = setInterval(() => {
            if (!this.isRefreshing) {
                this.checkPaymentStatus();
            }
        }, 30000);
    }

    clearAutoRefresh() {
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
            this.refreshInterval = null;
        }
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
    
    formatDate(dateString) {
        if (!dateString) return '-';
        try {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: 'numeric', 
                month: 'long', 
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        } catch (e) {
            return dateString;
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.paymentChecker = new PaymentStatusChecker();
});

// Global refresh function
function refreshPaymentStatus() {
    if (window.paymentChecker) {
        window.paymentChecker.refresh();
    }
}

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (window.paymentChecker) {
        window.paymentChecker.clearAutoRefresh();
    }
});

// Add the refresh method to the class
PaymentStatusChecker.prototype.refresh = function() {
    this.retryCount = 0;
    this.checkPaymentStatus();
};
</script>
@endsection
