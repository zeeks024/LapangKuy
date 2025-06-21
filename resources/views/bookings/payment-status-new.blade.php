@extends('layouts.app')

@section('title', 'Status Pembayaran - LapangKuy')

@push('styles')
<style>
    * {
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        overflow-x: hidden;
    }

    .status-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
        position: relative;
    }

    .status-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
        pointer-events: none;
    }

    .status-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 25px;
        padding: 3rem;
        max-width: 600px;
        width: 100%;
        box-shadow: 0 25px 50px rgba(0,0,0,0.15);
        text-align: center;
        position: relative;
        z-index: 1;
        border: 1px solid rgba(255,255,255,0.2);
    }

    .status-icon-wrapper {
        margin-bottom: 2rem;
        position: relative;
    }

    .status-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        position: relative;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .status-icon::before {
        content: '';
        position: absolute;
        top: -15px;
        left: -15px;
        right: -15px;
        bottom: -15px;
        border-radius: 50%;
        opacity: 0.3;
        transition: all 0.3s ease;
    }

    /* Loading State */
    .payment-status.loading .status-icon {
        background: linear-gradient(45deg, #e9ecef, #f8f9fa);
        color: #6c757d;
        animation: loadingPulse 2s ease-in-out infinite;
    }

    .payment-status.loading .status-icon::before {
        background: linear-gradient(45deg, #dee2e6, #e9ecef);
        animation: loadingRipple 2s ease-in-out infinite;
    }

    /* Success State */
    .payment-status.success .status-icon {
        background: linear-gradient(45deg, #10b981, #059669);
        color: white;
        animation: successBounce 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 25px 50px rgba(16, 185, 129, 0.4);
    }

    .payment-status.success .status-icon::before {
        background: linear-gradient(45deg, #10b981, #059669);
        animation: successGlow 2s ease-in-out infinite;
    }

    /* Pending State */
    .payment-status.pending .status-icon {
        background: linear-gradient(45deg, #f59e0b, #d97706);
        color: white;
        animation: pendingPulse 2s ease-in-out infinite;
        box-shadow: 0 25px 50px rgba(245, 158, 11, 0.4);
    }

    .payment-status.pending .status-icon::before {
        background: linear-gradient(45deg, #f59e0b, #d97706);
        animation: pendingRipple 2s ease-in-out infinite;
    }

    /* Failed State */
    .payment-status.failed .status-icon {
        background: linear-gradient(45deg, #ef4444, #dc2626);
        color: white;
        animation: failedShake 0.5s ease-in-out;
        box-shadow: 0 25px 50px rgba(239, 68, 68, 0.4);
    }

    .payment-status.failed .status-icon::before {
        background: linear-gradient(45deg, #ef4444, #dc2626);
    }

    /* Animations */
    @keyframes loadingPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    @keyframes loadingRipple {
        0%, 100% { transform: scale(1); opacity: 0.3; }
        50% { transform: scale(1.2); opacity: 0.1; }
    }

    @keyframes successBounce {
        0% { transform: scale(0); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

    @keyframes successGlow {
        0%, 100% { transform: scale(1); opacity: 0.3; }
        50% { transform: scale(1.1); opacity: 0.1; }
    }

    @keyframes pendingPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.08); }
    }

    @keyframes pendingRipple {
        0%, 100% { transform: scale(1); opacity: 0.3; }
        50% { transform: scale(1.3); opacity: 0.05; }
    }

    @keyframes failedShake {
        0%, 100% { transform: translateX(0); }
        20% { transform: translateX(-10px); }
        40% { transform: translateX(10px); }
        60% { transform: translateX(-5px); }
        80% { transform: translateX(5px); }
    }

    .status-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #1f2937;
        line-height: 1.2;
    }

    .status-description {
        font-size: 1.1rem;
        color: #6b7280;
        margin-bottom: 2.5rem;
        line-height: 1.6;
    }

    .status-details {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 20px;
        padding: 2rem;
        margin: 2rem 0;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .detail-item {
        text-align: left;
    }

    .detail-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .detail-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1f2937;
        word-break: break-word;
    }

    .amount-highlight {
        font-size: 1.5rem;
        font-weight: 700;
        color: #059669;
        text-align: center;
        padding: 1rem;
        background: rgba(16, 185, 129, 0.1);
        border-radius: 15px;
        margin-top: 1rem;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 2.5rem;
    }

    .btn-modern {
        padding: 1rem 2rem;
        border-radius: 15px;
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        min-width: 160px;
        position: relative;
        overflow: hidden;
    }

    .btn-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s ease;
    }

    .btn-modern:hover::before {
        left: 100%;
    }

    .btn-primary-modern {
        background: linear-gradient(45deg, #667eea, #764ba2);
        color: white;
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    }

    .btn-primary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-secondary-modern {
        background: white;
        color: #6b7280;
        border-color: #e5e7eb;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .btn-secondary-modern:hover {
        background: #f9fafb;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        color: #374151;
    }

    .btn-success-modern {
        background: linear-gradient(45deg, #10b981, #059669);
        color: white;
        box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
    }

    .btn-success-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(16, 185, 129, 0.4);
        color: white;
    }

    .refresh-btn {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        background: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #6b7280;
        backdrop-filter: blur(10px);
    }

    .refresh-btn:hover {
        background: rgba(102, 126, 234, 0.2);
        border-color: rgba(102, 126, 234, 0.4);
        color: #667eea;
        transform: rotate(180deg);
    }

    @media (max-width: 768px) {
        .status-container {
            padding: 1rem;
        }

        .status-card {
            padding: 2rem 1.5rem;
            border-radius: 20px;
        }

        .status-icon {
            width: 100px;
            height: 100px;
            font-size: 3rem;
        }

        .status-title {
            font-size: 2rem;
        }

        .detail-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .action-buttons {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-modern {
            width: 100%;
        }
    }

    /* Special effects for celebration */
    .celebration-confetti {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 1000;
    }

    .confetti-piece {
        position: absolute;
        width: 10px;
        height: 10px;
        background: #10b981;
        animation: confetti-fall 3s linear infinite;
    }

    @keyframes confetti-fall {
        0% {
            transform: translateY(-100vh) rotate(0deg);
            opacity: 1;
        }
        100% {
            transform: translateY(100vh) rotate(720deg);
            opacity: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="status-container">
    <div class="status-card">
        <button class="refresh-btn" onclick="refreshPaymentStatus()" title="Refresh Status">
            <i class="fas fa-sync-alt"></i>
        </button>

        <div id="paymentStatus" class="payment-status loading">
            <div class="status-icon-wrapper">
                <div class="status-icon">
                    <i class="fas fa-spinner fa-spin" id="statusIcon"></i>
                </div>
            </div>
            
            <h1 class="status-title" id="statusTitle">Mengecek Status Pembayaran...</h1>
            <p class="status-description" id="statusDescription">Mohon tunggu sebentar, kami sedang memverifikasi status pembayaran Anda</p>
        </div>

        <div class="status-details">
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">Order ID</div>
                    <div class="detail-value" id="orderId">LK-{{ $booking->id }}-{{ $booking->created_at->timestamp }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Lapangan</div>
                    <div class="detail-value" id="fieldName">{{ $booking->field->name ?? 'Loading...' }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Tanggal & Waktu</div>
                    <div class="detail-value" id="bookingDateTime">
                        {{ $booking->date ? \Carbon\Carbon::parse($booking->date)->format('d M Y') : 'Loading...' }}
                        <br><small>{{ $booking->start_time }} - {{ $booking->end_time }}</small>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Metode Pembayaran</div>
                    <div class="detail-value" id="paymentMethod">{{ $booking->payment_method ?? 'Menunggu...' }}</div>
                </div>
            </div>

            <div class="amount-highlight" id="totalAmount">
                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
            </div>

            <div class="detail-item" id="transactionDetails" style="display: none; margin-top: 1rem;">
                <div class="detail-label">ID Transaksi</div>
                <div class="detail-value" id="transactionId">-</div>
            </div>
        </div>

        <div class="action-buttons">
            <a href="{{ route('bookings.index') }}" class="btn-modern btn-primary-modern">
                <i class="fas fa-list"></i>
                Lihat Booking Saya
            </a>
            <a href="{{ route('fields.index') }}" class="btn-modern btn-secondary-modern">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Lapangan
            </a>
            @if($booking->payment_status === 'pending')
            <button class="btn-modern btn-success-modern" onclick="showUpdateStatusModal()" id="updateStatusBtn">
                <i class="fas fa-sync-alt"></i>
                Update Status Manual
            </button>
            @endif
        </div>
    </div>
</div>

<!-- Celebration Confetti (for successful payments) -->
<div class="celebration-confetti" id="celebrationConfetti" style="display: none;"></div>

<!-- Manual Status Update Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Update Status Pembayaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Update Manual:</strong> Gunakan fitur ini jika pembayaran sudah berhasil tapi status belum terupdate.
                </div>
                <div class="mb-3">
                    <label class="form-label">Status Pembayaran</label>
                    <select class="form-select" id="newPaymentStatus">
                        <option value="settlement">Berhasil (Settlement)</option>
                        <option value="pending">Pending</option>
                        <option value="failed">Gagal</option>
                        <option value="cancel">Dibatalkan</option>
                        <option value="expire">Kedaluwarsa</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">ID Transaksi (Opsional)</label>
                    <input type="text" class="form-control" id="transactionIdInput" placeholder="Masukkan ID transaksi jika ada">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="updatePaymentStatusManual()">Update Status</button>
            </div>
        </div>
    </div>
</div>

<script>
// Enhanced Payment Status Checker with SiTumbuh-style implementation
class PaymentStatusChecker {
    constructor() {
        this.bookingId = {{ $booking->id }};
        this.maxRetries = 3;
        this.retryCount = 0;
        this.refreshInterval = null;
        this.isRefreshing = false;

        this.elements = {
            paymentStatus: document.getElementById('paymentStatus'),
            statusIcon: document.getElementById('statusIcon'),
            statusTitle: document.getElementById('statusTitle'),
            statusDescription: document.getElementById('statusDescription'),
            orderId: document.getElementById('orderId'),
            fieldName: document.getElementById('fieldName'),
            bookingDateTime: document.getElementById('bookingDateTime'),
            paymentMethod: document.getElementById('paymentMethod'),
            totalAmount: document.getElementById('totalAmount'),
            transactionId: document.getElementById('transactionId'),
            transactionDetails: document.getElementById('transactionDetails')
        };

        this.init();
    }

    init() {
        // Get URL parameters for immediate status update
        const params = new URLSearchParams(window.location.search);
        const orderId = params.get('order_id');
        const status = params.get('transaction_status');

        // Display order ID if available from URL
        if (orderId) {
            this.elements.orderId.textContent = orderId;
        }

        // Check payment status
        this.checkPaymentStatus();

        // Auto-refresh for pending payments every 30 seconds
        this.setupAutoRefresh();
    }

    async checkPaymentStatus() {
        if (this.isRefreshing) return;
        
        this.isRefreshing = true;
        this.setLoadingState();

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
            console.log('Payment status response:', data);

            this.updatePaymentStatus(data);
            this.retryCount = 0;

        } catch (error) {
            console.error('Error checking payment status:', error);
            this.handleError(error);
        } finally {
            this.isRefreshing = false;
        }
    }

    setLoadingState() {
        this.elements.paymentStatus.className = 'payment-status loading';
        this.elements.statusIcon.className = 'fas fa-spinner fa-spin';
        this.elements.statusTitle.textContent = 'Mengecek Status Pembayaran...';
        this.elements.statusDescription.textContent = 'Mohon tunggu sebentar, kami sedang memverifikasi status pembayaran Anda';
    }

    updatePaymentStatus(data) {
        console.log('Updating status with data:', data);

        // Update booking details if available
        if (data.field_name && data.field_name !== 'Unknown Field') {
            this.elements.fieldName.textContent = data.field_name;
        }

        if (data.booking_date && data.booking_time) {
            this.elements.bookingDateTime.innerHTML = 
                this.formatDate(data.booking_date) + '<br><small>' + data.booking_time + '</small>';
        }

        // Update payment method
        if (data.payment_type && data.payment_type !== 'Unknown') {
            this.elements.paymentMethod.textContent = this.formatPaymentMethod(data.payment_type);
        }

        // Update amount
        if (data.gross_amount && data.gross_amount > 0) {
            this.elements.totalAmount.textContent = this.formatCurrency(data.gross_amount);
        }

        // Update order ID
        if (data.order_id) {
            this.elements.orderId.textContent = data.order_id;
        }

        // Update transaction ID if available
        if (data.transaction_id && data.transaction_id !== 'N/A' && !data.transaction_id.startsWith('TEST-')) {
            this.elements.transactionId.textContent = data.transaction_id;
            this.elements.transactionDetails.style.display = 'block';
        }

        // Remove loading class
        this.elements.paymentStatus.classList.remove('loading');

        // Update status based on transaction status
        const status = data.transaction_status || 'unknown';
        this.setStatusDisplay(status, data);

        // Setup auto-refresh for pending payments
        if (status === 'pending') {
            this.setupAutoRefresh();
        } else {
            this.clearAutoRefresh();
            
            // Hide update button if payment is successful
            const updateBtn = document.getElementById('updateStatusBtn');
            if (updateBtn && (status === 'settlement' || status === 'success' || status === 'paid')) {
                updateBtn.style.display = 'none';
            }
            
            // Show celebration for successful payments
            if (status === 'settlement' || status === 'success' || status === 'paid') {
                this.showCelebration();
            }
        }
    }

    setStatusDisplay(status, data) {
        // Remove all status classes
        this.elements.paymentStatus.classList.remove('success', 'pending', 'failed', 'unknown');

        switch (status) {
            case 'settlement':
            case 'capture':
            case 'paid':
            case 'success':
                this.elements.paymentStatus.classList.add('success');
                this.elements.statusIcon.className = 'fas fa-check-circle';
                this.elements.statusTitle.textContent = 'Pembayaran Berhasil!';
                this.elements.statusDescription.textContent = 'Selamat! Pembayaran Anda telah berhasil diproses. Booking lapangan Anda sudah dikonfirmasi dan siap digunakan.';
                break;

            case 'pending':
                this.elements.paymentStatus.classList.add('pending');
                this.elements.statusIcon.className = 'fas fa-clock';
                this.elements.statusTitle.textContent = 'Menunggu Pembayaran';
                this.elements.statusDescription.textContent = 'Pembayaran Anda sedang diproses. Halaman ini akan otomatis terupdate ketika pembayaran berhasil.';
                break;

            case 'deny':
            case 'cancel':
            case 'expire':
            case 'failed':
            case 'cancelled':
                this.elements.paymentStatus.classList.add('failed');
                this.elements.statusIcon.className = 'fas fa-times-circle';
                this.elements.statusTitle.textContent = 'Pembayaran Gagal';
                this.elements.statusDescription.textContent = data.message || 'Pembayaran tidak dapat diproses. Silakan coba lagi atau gunakan metode pembayaran lain.';
                break;

            default:
                this.elements.paymentStatus.classList.add('unknown');
                this.elements.statusIcon.className = 'fas fa-question-circle';
                this.elements.statusTitle.textContent = 'Status Tidak Diketahui';
                this.elements.statusDescription.textContent = data.message || 'Status pembayaran tidak dapat ditentukan. Silakan hubungi customer service kami untuk bantuan.';
        }
    }

    showCelebration() {
        const confettiContainer = document.getElementById('celebrationConfetti');
        confettiContainer.style.display = 'block';
        
        // Create confetti pieces
        for (let i = 0; i < 50; i++) {
            setTimeout(() => {
                const confetti = document.createElement('div');
                confetti.className = 'confetti-piece';
                confetti.style.left = Math.random() * 100 + '%';
                confetti.style.backgroundColor = ['#10b981', '#3b82f6', '#8b5cf6', '#f59e0b', '#ef4444'][Math.floor(Math.random() * 5)];
                confetti.style.animationDelay = Math.random() * 2 + 's';
                confettiContainer.appendChild(confetti);
                
                // Remove confetti after animation
                setTimeout(() => {
                    if (confetti.parentNode) {
                        confetti.parentNode.removeChild(confetti);
                    }
                }, 3000);
            }, i * 100);
        }
        
        // Hide confetti container after 5 seconds
        setTimeout(() => {
            confettiContainer.style.display = 'none';
        }, 5000);
    }

    handleError(error) {
        this.retryCount++;
        
        if (this.retryCount < this.maxRetries) {
            setTimeout(() => {
                console.log(`Retrying... Attempt ${this.retryCount + 1}/${this.maxRetries}`);
                this.checkPaymentStatus();
            }, 2000 * this.retryCount);
            
            this.elements.statusDescription.textContent = `Mencoba kembali... (${this.retryCount}/${this.maxRetries})`;
        } else {
            this.elements.paymentStatus.classList.remove('loading');
            this.elements.paymentStatus.classList.add('unknown');
            this.elements.statusIcon.className = 'fas fa-exclamation-triangle';
            this.elements.statusTitle.textContent = 'Koneksi Bermasalah';
            this.elements.statusDescription.textContent = 'Tidak dapat mengecek status pembayaran. Periksa koneksi internet Anda dan coba lagi.';
        }
    }

    setupAutoRefresh() {
        this.clearAutoRefresh();
        
        this.refreshInterval = setInterval(() => {
            if (!this.isRefreshing) {
                console.log('Auto-refreshing payment status...');
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

    refresh() {
        this.retryCount = 0;
        this.checkPaymentStatus();
    }

    formatPaymentMethod(method) {
        if (!method || method === 'Unknown') return 'Belum Diproses';

        const methods = {
            'credit_card': 'Kartu Kredit',
            'bank_transfer': 'Transfer Bank',
            'settlement': 'Pembayaran Berhasil',
            'manual_update': 'Update Manual',
            'test_payment': 'Pembayaran Test'
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
        if (!dateString) return 'Loading...';

        try {
            const date = new Date(dateString);
            if (isNaN(date)) return dateString;

            return date.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'short',
                year: 'numeric'
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

// Global functions
function refreshPaymentStatus() {
    if (window.paymentChecker) {
        window.paymentChecker.refresh();
    }
}

function showUpdateStatusModal() {
    const modal = new bootstrap.Modal(document.getElementById('updateStatusModal'));
    modal.show();
}

async function updatePaymentStatusManual() {
    const newStatus = document.getElementById('newPaymentStatus').value;
    const transactionId = document.getElementById('transactionIdInput').value;
    
    try {
        const response = await fetch('{{ route("bookings.payment.update-status", $booking) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                transaction_status: newStatus,
                payment_type: 'manual_update',
                transaction_id: transactionId || null
            })
        });

        const data = await response.json();
        
        if (data.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('updateStatusModal'));
            modal.hide();
            
            // Show success notification
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
            alert.style.top = '20px';
            alert.style.right = '20px';
            alert.style.zIndex = '9999';
            alert.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>Status pembayaran berhasil diupdate!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alert);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 5000);
            
            // Refresh status
            if (window.paymentChecker) {
                window.paymentChecker.refresh();
            }
        } else {
            alert('Gagal mengupdate status: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error updating payment status:', error);
        alert('Terjadi kesalahan saat mengupdate status pembayaran.');
    }
}

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (window.paymentChecker) {
        window.paymentChecker.clearAutoRefresh();
    }
});
</script>
@endsection
