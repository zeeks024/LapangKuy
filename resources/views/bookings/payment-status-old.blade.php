@extends('layouts.app')

@section('title', 'Status Pembayaran - LapangKuy')

@push('styles')
<style>
    * {
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .payment-status-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .status-header {
        text-align: center;
        margin-bottom: 3rem;
        padding: 3rem 2rem;
        background: linear-gradient(135deg, #043E03 0%, #0A5C08 50%, #0F7B0F 100%);
        border-radius: 25px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .status-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/>',
        opacity: 0.3;
    }

    .status-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .status-header p {
        font-size: 1.1rem;
        opacity: 0.9;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .main-status-card {
        background: white;
        border-radius: 25px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-bottom: 2rem;
        position: relative;
    }

    .status-display {
        padding: 3rem 2rem;
        text-align: center;
        position: relative;
    }

    .refresh-button {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        background: rgba(255,255,255,0.1);
        border: 2px solid rgba(255,255,255,0.2);
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #6c757d;
    }

    .refresh-button:hover {
        background: rgba(4, 62, 3, 0.1);
        border-color: #043E03;
        color: #043E03;
        transform: rotate(180deg);
    }

    .status-icon-container {
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
        font-size: 3.5rem;
        position: relative;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .status-icon::before {
        content: '';
        position: absolute;
        top: -10px;
        left: -10px;
        right: -10px;
        bottom: -10px;
        border-radius: 50%;
        opacity: 0.2;
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
        background: linear-gradient(45deg, #28a745, #20c997);
        color: white;
        animation: successBounce 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 20px 40px rgba(40, 167, 69, 0.3);
    }

    .payment-status.success .status-icon::before {
        background: linear-gradient(45deg, #28a745, #20c997);
        animation: successGlow 2s ease-in-out infinite;
    }

    /* Pending State */
    .payment-status.pending .status-icon {
        background: linear-gradient(45deg, #ffc107, #fd7e14);
        color: white;
        animation: pendingPulse 2s ease-in-out infinite;
        box-shadow: 0 20px 40px rgba(255, 193, 7, 0.3);
    }

    .payment-status.pending .status-icon::before {
        background: linear-gradient(45deg, #ffc107, #fd7e14);
        animation: pendingRipple 2s ease-in-out infinite;
    }

    /* Failed State */
    .payment-status.failed .status-icon {
        background: linear-gradient(45deg, #dc3545, #e91e63);
        color: white;
        animation: failedShake 0.5s ease-in-out;
        box-shadow: 0 20px 40px rgba(220, 53, 69, 0.3);
    }

    .payment-status.failed .status-icon::before {
        background: linear-gradient(45deg, #dc3545, #e91e63);
    }

    /* Unknown State */
    .payment-status.unknown .status-icon {
        background: linear-gradient(45deg, #6c757d, #495057);
        color: white;
        box-shadow: 0 20px 40px rgba(108, 117, 125, 0.3);
    }

    /* Animations */
    @keyframes loadingPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    @keyframes loadingRipple {
        0%, 100% { transform: scale(1); opacity: 0.2; }
        50% { transform: scale(1.2); opacity: 0.1; }
    }

    @keyframes successBounce {
        0% { transform: scale(0); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

    @keyframes successGlow {
        0%, 100% { transform: scale(1); opacity: 0.2; }
        50% { transform: scale(1.1); opacity: 0.1; }
    }

    @keyframes pendingPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.08); }
    }

    @keyframes pendingRipple {
        0%, 100% { transform: scale(1); opacity: 0.2; }
        50% { transform: scale(1.3); opacity: 0.05; }
    }

    @keyframes failedShake {
        0%, 100% { transform: translateX(0); }
        20% { transform: translateX(-10px); }
        40% { transform: translateX(10px); }
        60% { transform: translateX(-5px); }
        80% { transform: translateX(5px); }
    }

    .status-text h2 {
        font-size: 2.2rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 1rem;
        letter-spacing: -0.02em;
    }

    .status-description {
        font-size: 1.1rem;
        color: #6c757d;
        line-height: 1.6;
        margin-bottom: 2rem;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }

    .booking-details-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 2.5rem;
        margin: 0;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        max-width: 800px;
        margin: 0 auto;
    }

    .detail-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .detail-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .detail-card .icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 1.2rem;
        color: white;
    }

    .detail-card.order .icon { background: linear-gradient(45deg, #6f42c1, #e83e8c); }
    .detail-card.field .icon { background: linear-gradient(45deg, #043E03, #0A5C08); }
    .detail-card.time .icon { background: linear-gradient(45deg, #17a2b8, #007bff); }
    .detail-card.payment .icon { background: linear-gradient(45deg, #ffc107, #fd7e14); }
    .detail-card.amount .icon { background: linear-gradient(45deg, #28a745, #20c997); }
    .detail-card.transaction .icon { background: linear-gradient(45deg, #6c757d, #495057); }

    .detail-card .label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .detail-card .value {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
        line-height: 1.4;
    }

    .action-section {
        padding: 2.5rem;
        text-align: center;
        background: white;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
        max-width: 600px;
        margin: 0 auto;
    }

    .btn {
        padding: 1rem 2rem;
        border-radius: 12px;
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

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s ease;
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn.primary {
        background: linear-gradient(45deg, #043E03, #0A5C08);
        color: white;
        box-shadow: 0 5px 15px rgba(4, 62, 3, 0.3);
    }

    .btn.primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(4, 62, 3, 0.4);
        color: white;
    }

    .btn.secondary {
        background: white;
        color: #6c757d;
        border-color: #e9ecef;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .btn.secondary:hover {
        background: #f8f9fa;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        color: #495057;
    }

    .btn.outline {
        background: transparent;
        color: #043E03;
        border-color: #043E03;
    }

    .btn.outline:hover {
        background: #043E03;
        color: white;
        transform: translateY(-2px);
    }

    .btn.success {
        background: linear-gradient(45deg, #28a745, #20c997);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px 24px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn.success:hover {
        background: linear-gradient(45deg, #20c997, #17a2b8);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        color: white;
    }

    .modal-content {
        border-radius: 15px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .modal-header {
        background: linear-gradient(135deg, #043E03 0%, #0A5C08 100%);
        color: white;
        border-radius: 15px 15px 0 0;
        border-bottom: none;
    }

    .modal-title {
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .payment-status-container {
            padding: 1rem 0.5rem;
        }

        .status-header {
            padding: 2rem 1rem;
            margin-bottom: 2rem;
        }

        .status-header h1 {
            font-size: 2rem;
        }

        .status-display {
            padding: 2rem 1rem;
        }

        .status-icon {
            width: 100px;
            height: 100px;
            font-size: 3rem;
        }

        .status-text h2 {
            font-size: 1.8rem;
        }

        .booking-details-section {
            padding: 2rem 1rem;
        }

        .details-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .action-buttons {
            flex-direction: column;
            align-items: stretch;
        }

        .btn {
            width: 100%;
        }
    }
        }
</style>
@endpush

@section('content')
<div class="payment-status-container">
    <!-- Status Header -->
    <div class="status-header">
        <h1><i class="fas fa-credit-card me-3"></i>Status Pembayaran</h1>
        <p>Monitoring pembayaran booking lapangan LapangKuy Anda</p>
    </div>

    <!-- Main Status Card -->
    <div class="main-status-card">
        <!-- Status Display Section -->
        <div class="status-display">
            <button class="refresh-button" onclick="refreshPaymentStatus()" title="Refresh Status">
                <i class="fas fa-sync-alt"></i>
            </button>

            <div id="paymentStatus" class="payment-status loading">
                <div class="status-icon-container">
                    <div class="status-icon">
                        <i class="fas fa-spinner fa-spin" id="statusIcon"></i>
                    </div>
                </div>
                
                <div class="status-text">
                    <h2 id="statusText">Mengecek Status Pembayaran...</h2>
                    <p class="status-description" id="statusDescription">Mohon tunggu sebentar, kami sedang memverifikasi status pembayaran Anda</p>
                </div>
            </div>
        </div>

        <!-- Booking Details Section -->
        <div class="booking-details-section">
            <div class="details-grid">
                <div class="detail-card order">
                    <div class="icon">
                        <i class="fas fa-hashtag"></i>
                    </div>
                    <div class="label">Order ID</div>
                    <div class="value" id="orderId">LK-{{ $booking->id }}-{{ $booking->created_at->timestamp }}</div>
                </div>

                <div class="detail-card field">
                    <div class="icon">
                        <i class="fas fa-futbol"></i>
                    </div>
                    <div class="label">Lapangan</div>
                    <div class="value" id="fieldName">{{ $booking->field->name ?? 'Loading...' }}</div>
                </div>

                <div class="detail-card time">
                    <div class="icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="label">Tanggal & Waktu</div>
                    <div class="value" id="bookingDateTime">
                        {{ $booking->date ? \Carbon\Carbon::parse($booking->date)->format('d M Y') : 'Loading...' }}
                        <br>
                        <small>{{ $booking->start_time }} - {{ $booking->end_time }}</small>
                    </div>
                </div>

                <div class="detail-card payment">
                    <div class="icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="label">Metode Pembayaran</div>
                    <div class="value" id="paymentMethod">{{ $booking->payment_method ?? 'Menunggu...' }}</div>
                </div>

                <div class="detail-card amount">
                    <div class="icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="label">Total Pembayaran</div>
                    <div class="value" id="totalAmount">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                </div>

                <div class="detail-card transaction" id="transactionCard" style="display: none;">
                    <div class="icon">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div class="label">ID Transaksi</div>
                    <div class="value" id="transactionId">-</div>
                </div>
            </div>
        </div>        <!-- Action Buttons Section -->
        <div class="action-section">
            <div class="action-buttons">
                <a href="{{ route('fields.index') }}" class="btn secondary">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Lapangan
                </a>
                <a href="{{ route('bookings.index') }}" class="btn primary">
                    <i class="fas fa-list"></i>
                    Lihat Booking Saya
                </a>
                <a href="{{ route('bookings.payments') }}" class="btn outline">
                    <i class="fas fa-history"></i>
                    Riwayat Pembayaran
                </a>
                
                <!-- Manual Status Update Button (SiTumbuh style) -->
                @if($booking->payment_status === 'pending')
                <button class="btn success" onclick="showUpdateStatusModal()" id="updateStatusBtn">
                    <i class="fas fa-sync-alt"></i>
                    Update Status Manual
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Manual Status Update Modal (SiTumbuh style) -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Status Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                    <input type="text" class="form-control" id="transactionId" placeholder="Masukkan ID transaksi jika ada">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="updatePaymentStatus()">Update Status</button>
            </div>
        </div>
    </div>
</div>

<script>
// Enhanced Payment Status Checker with better error handling and UX
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
        // Get URL parameters
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
    }    async checkPaymentStatus() {
        if (this.isRefreshing) return;
        
        this.isRefreshing = true;
        this.setLoadingState();

        // SiTumbuh style: simple GET request with booking_id
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

            // SiTumbuh style: validate required fields
            if (!data.success) {
                throw new Error(data.message || 'API returned error');
            }

            this.updatePaymentStatus(data);
            this.retryCount = 0; // Reset retry count on success

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
        this.elements.statusText.textContent = 'Mengecek Status Pembayaran...';
        this.elements.statusDescription.textContent = 'Mohon tunggu sebentar, kami sedang memverifikasi status pembayaran Anda';
    }    updatePaymentStatus(data) {
        console.log('Updating status with data:', data);

        // SiTumbuh style: Update all fields with validation
        try {
            // Update booking details
            if (data.field_name && data.field_name !== 'Unknown Field') {
                this.elements.fieldName.textContent = data.field_name;
            }

            if (data.booking_date && data.booking_time) {
                this.elements.bookingDateTime.innerHTML = 
                    this.formatDate(data.booking_date) + '<br><small>' + data.booking_time + '</small>';
            }

            // Update payment method with proper formatting
            if (data.payment_type && data.payment_type !== 'Unknown') {
                this.elements.paymentMethod.textContent = this.formatPaymentMethod(data.payment_type);
            }

            // Update amount - critical fix
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
                this.elements.transactionCard.style.display = 'block';
            }

            // Remove loading class first
            this.elements.paymentStatus.classList.remove('loading');

            // Set status display - this is the critical part
            const status = data.transaction_status || 'unknown';
            console.log('Setting status display for:', status);
            this.setStatusDisplay(status, data);

            // Auto-refresh logic
            if (status === 'pending') {
                this.setupAutoRefresh();
            } else {
                this.clearAutoRefresh();
                // Hide update button if payment is successful
                const updateBtn = document.getElementById('updateStatusBtn');
                if (updateBtn && (status === 'settlement' || status === 'success')) {
                    updateBtn.style.display = 'none';
                }
            }

        } catch (error) {
            console.error('Error updating payment status UI:', error);
            this.setStatusDisplay('unknown', { message: 'Error updating display' });
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
                this.elements.statusText.textContent = 'Pembayaran Berhasil!';
                this.elements.statusDescription.textContent = 'Selamat! Pembayaran Anda telah berhasil diproses. Booking lapangan Anda sudah dikonfirmasi dan siap digunakan.';
                break;

            case 'pending':
                this.elements.paymentStatus.classList.add('pending');
                this.elements.statusIcon.className = 'fas fa-clock';
                this.elements.statusText.textContent = 'Menunggu Pembayaran';
                this.elements.statusDescription.textContent = 'Pembayaran Anda sedang diproses. Halaman ini akan otomatis terupdate ketika pembayaran berhasil.';
                break;

            case 'deny':
            case 'cancel':
            case 'expire':
            case 'failed':
            case 'cancelled':
                this.elements.paymentStatus.classList.add('failed');
                this.elements.statusIcon.className = 'fas fa-times-circle';
                this.elements.statusText.textContent = 'Pembayaran Gagal';
                this.elements.statusDescription.textContent = data.message || 'Pembayaran tidak dapat diproses. Silakan coba lagi atau gunakan metode pembayaran lain.';
                break;

            default:
                this.elements.paymentStatus.classList.add('unknown');
                this.elements.statusIcon.className = 'fas fa-question-circle';
                this.elements.statusText.textContent = 'Status Tidak Diketahui';
                this.elements.statusDescription.textContent = data.message || 'Status pembayaran tidak dapat ditentukan. Silakan hubungi customer service kami untuk bantuan.';
        }
    }

    handleError(error) {
        this.retryCount++;
        
        if (this.retryCount < this.maxRetries) {
            // Retry after delay
            setTimeout(() => {
                console.log(`Retrying... Attempt ${this.retryCount + 1}/${this.maxRetries}`);
                this.checkPaymentStatus();
            }, 2000 * this.retryCount); // Exponential backoff
            
            this.elements.statusDescription.textContent = `Mencoba kembali... (${this.retryCount}/${this.maxRetries})`;
        } else {
            // Max retries reached
            this.elements.paymentStatus.classList.remove('loading');
            this.elements.paymentStatus.classList.add('unknown');
            this.elements.statusIcon.className = 'fas fa-exclamation-triangle';
            this.elements.statusText.textContent = 'Koneksi Bermasalah';
            this.elements.statusDescription.textContent = 'Tidak dapat mengecek status pembayaran. Periksa koneksi internet Anda dan coba lagi.';
        }
    }

    setupAutoRefresh() {
        this.clearAutoRefresh();
        
        // Only auto-refresh for pending payments
        this.refreshInterval = setInterval(() => {
            if (!this.isRefreshing) {
                console.log('Auto-refreshing payment status...');
                this.checkPaymentStatus();
            }
        }, 30000); // 30 seconds
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
            'cimb_clicks': 'CIMB Clicks',
            'bca_klikpay': 'BCA KlikPay',
            'bca_klikbca': 'KlikBCA',
            'bri_epay': 'BRI e-Pay',
            'echannel': 'Mandiri Bill',
            'permata_va': 'Permata Virtual Account',
            'bca_va': 'BCA Virtual Account',
            'bni_va': 'BNI Virtual Account',
            'bri_va': 'BRI Virtual Account',
            'mandiri_va': 'Mandiri Virtual Account',
            'other_va': 'Virtual Account',
            'gopay': 'GoPay',
            'shopeepay': 'ShopeePay',
            'ovo': 'OVO',
            'dana': 'DANA',
            'linkaja': 'LinkAja',
            'indomaret': 'Indomaret',
            'alfamart': 'Alfamart',
            'danamon_online': 'Danamon Online',
            'akulaku': 'Akulaku'
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

// Global refresh function for the refresh button
function refreshPaymentStatus() {
    if (window.paymentChecker) {
        window.paymentChecker.refresh();
    }
}

// SiTumbuh-style manual status update functions
function showUpdateStatusModal() {
    const modal = new bootstrap.Modal(document.getElementById('updateStatusModal'));
    modal.show();
}

async function updatePaymentStatus() {
    const newStatus = document.getElementById('newPaymentStatus').value;
    const transactionId = document.getElementById('transactionId').value;
    
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
            // Hide modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('updateStatusModal'));
            modal.hide();
            
            // Show success message
            alert('Status pembayaran berhasil diupdate!');
            
            // Refresh the page or update the status display
            if (window.paymentChecker) {
                window.paymentChecker.refresh();
            } else {
                location.reload();
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
