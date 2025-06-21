@extends('layouts.app')

@section('title', 'Status Pembayaran - LapangKuy')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    * {
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #f5576c 75%, #4facfe 100%);
        background-size: 400% 400%;
        animation: gradientShift 15s ease infinite;
        min-height: 100vh;
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        position: relative;
        overflow-x: hidden;
    }

    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* Enhanced Floating Diamonds */
    .floating-diamonds {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: -1;
        overflow: hidden;
    }

    .diamond {
        position: absolute;
        width: 12px;
        height: 12px;
        background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
        transform: rotate(45deg);
        border-radius: 2px;
        animation: floatDiamond 20s linear infinite;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
    }

    @keyframes floatDiamond {
        0% { 
            transform: translateY(100vh) rotate(45deg) scale(0.5);
            opacity: 0;
        }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { 
            transform: translateY(-100px) rotate(405deg) scale(1.2);
            opacity: 0;
        }
    }

    .payment-status-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 2rem 1rem;
        position: relative;
        z-index: 1;
    }

    .status-header {
        text-align: center;
        margin-bottom: 3rem;
        padding: 4rem 2.5rem;
        background: linear-gradient(135deg, rgba(67, 56, 202, 0.95) 0%, rgba(147, 51, 234, 0.95) 50%, rgba(236, 72, 153, 0.95) 100%);
        border-radius: 30px;
        color: white;
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 
            0 25px 50px rgba(0, 0, 0, 0.15),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
        animation: headerGlow 3s ease-in-out infinite alternate;
        transform: perspective(1000px) rotateX(5deg);
    }

    @keyframes headerGlow {
        0% { 
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15), 
            inset 0 1px 0 rgba(255, 255, 255, 0.2), 
            0 0 30px rgba(147, 51, 234, 0.3); 
        }
        100% { 
            box-shadow: 0 35px 70px rgba(0, 0, 0, 0.2), 
            inset 0 1px 0 rgba(255, 255, 255, 0.3), 
            0 0 50px rgba(236, 72, 153, 0.4); 
        }
    }

    .status-header h1 {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1rem;
        position: relative;
        z-index: 2;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        letter-spacing: -0.02em;
        animation: titleBounce 2s ease-out;
    }

    @keyframes titleBounce {
        0% { transform: translateY(-30px); opacity: 0; }
        60% { transform: translateY(10px); opacity: 0.8; }
        100% { transform: translateY(0); opacity: 1; }
    }

    .main-status-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 30px;
        box-shadow: 
            0 30px 60px rgba(0, 0, 0, 0.1),
            0 0 0 1px rgba(255, 255, 255, 0.5);
        overflow: hidden;
        margin-bottom: 2rem;
        position: relative;
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        animation: cardSlideUp 1s ease-out 0.5s both;
        transform: perspective(1000px) rotateY(-2deg);
        transition: all 0.6s cubic-bezier(0.23, 1, 0.320, 1);
    }

    .main-status-card:hover {
        transform: perspective(1000px) rotateY(0deg) translateY(-10px);
        box-shadow: 
            0 40px 80px rgba(0, 0, 0, 0.15),
            0 0 0 1px rgba(255, 255, 255, 0.6);
    }

    @keyframes cardSlideUp {
        0% { transform: perspective(1000px) rotateY(-2deg) translateY(50px); opacity: 0; }
        100% { transform: perspective(1000px) rotateY(-2deg) translateY(0); opacity: 1; }
    }

    .status-display {
        padding: 4rem 2.5rem;
        text-align: center;
        position: relative;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
    }

    .refresh-button {
        position: absolute;
        top: 2rem;
        right: 2rem;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
        color: #667eea;
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .refresh-button:hover {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-color: rgba(255, 255, 255, 0.5);
        color: white;
        transform: rotate(180deg) scale(1.1);
        box-shadow: 0 12px 30px rgba(102, 126, 234, 0.3);
    }

    .status-icon-container {
        margin-bottom: 2.5rem;
        position: relative;
    }

    .status-icon {
        width: 140px;
        height: 140px;
        margin: 0 auto;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        position: relative;
        transition: all 0.6s cubic-bezier(0.23, 1, 0.320, 1);
        animation: iconFloat 3s ease-in-out infinite;
    }

    @keyframes iconFloat {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    /* Status States */
    .payment-status.loading .status-icon {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        animation: loadingPulse 2s ease-in-out infinite, iconFloat 3s ease-in-out infinite;
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.4);
    }

    .payment-status.success .status-icon {
        background: linear-gradient(135deg, #10b981, #06d6a0);
        color: white;
        animation: successBounce 1s cubic-bezier(0.68, -0.55, 0.265, 1.55), iconFloat 3s ease-in-out infinite 1s;
        box-shadow: 0 25px 50px rgba(16, 185, 129, 0.4);
    }

    .payment-status.pending .status-icon {
        background: linear-gradient(135deg, #f59e0b, #f97316);
        color: white;
        animation: pendingPulse 2s ease-in-out infinite, iconFloat 3s ease-in-out infinite;
        box-shadow: 0 25px 50px rgba(245, 158, 11, 0.4);
    }

    .payment-status.failed .status-icon {
        background: linear-gradient(135deg, #ef4444, #f97171);
        color: white;
        animation: failedShake 0.6s ease-in-out, iconFloat 3s ease-in-out infinite 0.6s;
        box-shadow: 0 25px 50px rgba(239, 68, 68, 0.4);
    }

    @keyframes loadingPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.08); }
    }

    @keyframes successBounce {
        0% { transform: scale(0) rotate(-180deg); }
        50% { transform: scale(1.3) rotate(-90deg); }
        75% { transform: scale(0.9) rotate(-45deg); }
        100% { transform: scale(1) rotate(0deg); }
    }

    @keyframes pendingPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    @keyframes failedShake {
        0%, 100% { transform: translateX(0); }
        10% { transform: translateX(-10px) rotate(-2deg); }
        20% { transform: translateX(10px) rotate(2deg); }
        30% { transform: translateX(-10px) rotate(-2deg); }
        40% { transform: translateX(10px) rotate(2deg); }
        50% { transform: translateX(-5px) rotate(-1deg); }
        60% { transform: translateX(5px) rotate(1deg); }
        70% { transform: translateX(-5px) rotate(-1deg); }
        80% { transform: translateX(5px) rotate(1deg); }
        90% { transform: translateX(-2px); }
    }

    .status-text h2 {
        font-size: 2.8rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 1.5rem;
        letter-spacing: -0.03em;
        animation: textSlideIn 1s ease-out 0.8s both;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    @keyframes textSlideIn {
        0% { transform: translateY(30px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }

    .status-description {
        font-size: 1.2rem;
        color: #64748b;
        line-height: 1.7;
        margin-bottom: 2.5rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        font-weight: 500;
        animation: textSlideIn 1s ease-out 1s both;
    }

    .booking-details-section {
        background: linear-gradient(135deg, rgba(248, 250, 252, 0.8) 0%, rgba(241, 245, 249, 0.8) 100%);
        padding: 3rem 2.5rem;
        margin: 0;
        backdrop-filter: blur(10px);
        border-top: 1px solid rgba(255, 255, 255, 0.5);
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        max-width: 900px;
        margin: 0 auto;
        animation: gridSlideIn 1s ease-out 1.2s both;
    }

    @keyframes gridSlideIn {
        0% { transform: translateY(40px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }

    .detail-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 20px;
        padding: 2rem 1.5rem;
        box-shadow: 
            0 10px 25px rgba(0, 0, 0, 0.08),
            0 0 0 1px rgba(255, 255, 255, 0.5);
        transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(20px);
        position: relative;
        overflow: hidden;
        animation: cardFadeIn 0.6s ease-out calc(var(--delay, 0) * 0.1s) both;
        cursor: pointer;
    }

    .detail-card:nth-child(1) { --delay: 1; }
    .detail-card:nth-child(2) { --delay: 2; }
    .detail-card:nth-child(3) { --delay: 3; }
    .detail-card:nth-child(4) { --delay: 4; }
    .detail-card:nth-child(5) { --delay: 5; }
    .detail-card:nth-child(6) { --delay: 6; }

    @keyframes cardFadeIn {
        0% { transform: translateY(30px) scale(0.95); opacity: 0; }
        100% { transform: translateY(0) scale(1); opacity: 1; }
    }

    .detail-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 
            0 20px 40px rgba(0, 0, 0, 0.12),
            0 0 0 1px rgba(255, 255, 255, 0.6);
    }

    .detail-card .icon {
        width: 50px;
        height: 50px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        font-size: 1.4rem;
        color: white;
        transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .detail-card:hover .icon {
        transform: translateY(-5px) rotate(5deg);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
    }

    .detail-card.order .icon { 
        background: linear-gradient(135deg, #667eea, #764ba2); 
    }
    .detail-card.field .icon { 
        background: linear-gradient(135deg, #10b981, #059669); 
    }
    .detail-card.time .icon { 
        background: linear-gradient(135deg, #3b82f6, #1d4ed8); 
    }
    .detail-card.payment .icon { 
        background: linear-gradient(135deg, #f59e0b, #d97706); 
    }
    .detail-card.amount .icon { 
        background: linear-gradient(135deg, #06d6a0, #059669); 
    }
    .detail-card.transaction .icon { 
        background: linear-gradient(135deg, #8b5cf6, #7c3aed); 
    }

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

    .btn:hover {
        transform: translateY(-2px);
        animation: buttonPulse 0.6s ease-out;
    }

    @keyframes buttonPulse {
        0% { transform: translateY(-2px) scale(1); }
        50% { transform: translateY(-2px) scale(1.05); }
        100% { transform: translateY(-2px) scale(1); }
    }

    .btn.primary {
        background: linear-gradient(45deg, #043E03, #0A5C08);
        color: white;
        box-shadow: 0 5px 15px rgba(4, 62, 3, 0.3);
    }

    .btn.primary:hover {
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
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        color: #495057;
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

    /* Enhanced confetti for success */
    .confetti-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 10000;
        overflow: hidden;
    }

    .confetti {
        position: absolute;
        width: 10px;
        height: 10px;
        background: #f43f5e;
        animation: confetti-fall 3s linear infinite;
    }

    .confetti:nth-child(odd) { background: #06d6a0; animation-delay: -0.5s; }
    .confetti:nth-child(even) { background: #fbbf24; animation-delay: -1s; }
    .confetti:nth-child(3n) { background: #3b82f6; animation-delay: -1.5s; }

    @keyframes confetti-fall {
        0% {
            transform: translateY(-100vh) rotate(0deg);
            opacity: 1;
        }
        100% {
            transform: translateY(100vh) rotate(360deg);
            opacity: 0;
        }
    }

    /* Magical spinner */
    .magical-spinner {
        display: inline-block;
        width: 60px;
        height: 60px;
        position: relative;
    }

    .magical-spinner::before,
    .magical-spinner::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        animation: magicalSpinner 2s linear infinite;
    }

    .magical-spinner::before {
        width: 60px;
        height: 60px;
        border: 3px solid transparent;
        border-top: 3px solid #667eea;
        border-right: 3px solid #764ba2;
    }

    .magical-spinner::after {
        width: 40px;
        height: 40px;
        top: 10px;
        left: 10px;
        border: 3px solid transparent;
        border-bottom: 3px solid #f093fb;
        border-left: 3px solid #f5576c;
        animation-direction: reverse;
        animation-duration: 1.5s;
    }

    @keyframes magicalSpinner {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .floating-diamonds { display: none; }
        .payment-status-container { padding: 1rem 0.5rem; }
        .status-header { 
            padding: 2.5rem 1.5rem;
            transform: none;
        }
        .status-header h1 { font-size: 2.2rem; }
        .main-status-card { transform: none; }
        .details-grid { grid-template-columns: 1fr; gap: 1.5rem; }
        .action-buttons { flex-direction: column; }
        .btn { width: 100%; }
    }

    /* Accessibility */
    @media (prefers-reduced-motion: reduce) {
        * { animation-duration: 0.01ms !important; }
        .floating-diamonds { display: none !important; }
    }

    .btn:focus-visible {
        outline: 3px solid #667eea;
        outline-offset: 2px;
        box-shadow: 0 0 0 6px rgba(102, 126, 234, 0.2);
    }
</style>
@endpush

@section('content')
<!-- Enhanced Floating Diamonds Background -->
<div class="floating-diamonds">
    <div class="diamond" style="left: 5%; animation-delay: 0s; animation-duration: 25s;"></div>
    <div class="diamond" style="left: 15%; animation-delay: 3s; animation-duration: 18s;"></div>
    <div class="diamond" style="left: 25%; animation-delay: 6s; animation-duration: 22s;"></div>
    <div class="diamond" style="left: 35%; animation-delay: 9s; animation-duration: 20s;"></div>
    <div class="diamond" style="left: 45%; animation-delay: 12s; animation-duration: 24s;"></div>
    <div class="diamond" style="left: 55%; animation-delay: 15s; animation-duration: 19s;"></div>
    <div class="diamond" style="left: 65%; animation-delay: 18s; animation-duration: 23s;"></div>
    <div class="diamond" style="left: 75%; animation-delay: 21s; animation-duration: 21s;"></div>
    <div class="diamond" style="left: 85%; animation-delay: 24s; animation-duration: 26s;"></div>
    <div class="diamond" style="left: 95%; animation-delay: 27s; animation-duration: 17s;"></div>
</div>

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
                    <div class="status-icon" id="statusIcon">
                        <i class="fas fa-spinner fa-spin"></i>
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
        </div>

        <!-- Action Buttons Section -->
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

<script>
// Enhanced Payment Status Checker
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
                this.setStatusDisplay('success', {
                    message: 'Pembayaran berhasil! Booking Anda telah dikonfirmasi.'
                });
                break;
            case 'pending':
                this.setStatusDisplay('pending', {
                    message: 'Pembayaran sedang diproses. Mohon tunggu konfirmasi.'
                });
                break;
            case 'deny':
            case 'cancel':
            case 'expire':
            case 'failed':
                this.setStatusDisplay('failed', {
                    message: 'Pembayaran gagal atau dibatalkan.'
                });
                break;
        }
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

            if (!data.success) {
                throw new Error(data.message || 'API returned error');
            }

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
        this.elements.statusIcon.innerHTML = '';
        this.elements.statusIcon.className = 'magical-spinner';
        
        this.elements.statusText.textContent = 'Mengecek Status Pembayaran...';
        this.elements.statusDescription.innerHTML = `
            <div style="display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 1rem;">
                <span>Memverifikasi pembayaran Anda</span>
            </div>
            <div style="font-size: 0.9rem; opacity: 0.8;">
                Halaman akan otomatis terupdate dalam beberapa saat...
            </div>
        `;
    }

    updatePaymentStatus(data) {
        try {
            if (data.field_name && data.field_name !== 'Unknown Field') {
                this.elements.fieldName.textContent = data.field_name;
            }

            if (data.payment_type && data.payment_type !== 'Unknown') {
                this.elements.paymentMethod.textContent = this.formatPaymentMethod(data.payment_type);
            }

            if (data.gross_amount && data.gross_amount !== 'N/A') {
                this.elements.totalAmount.textContent = this.formatCurrency(data.gross_amount);
            }

            if (data.transaction_id && data.transaction_id !== 'N/A' && !data.transaction_id.startsWith('TEST-')) {
                this.elements.transactionId.textContent = data.transaction_id;
                this.elements.transactionCard.style.display = 'block';
            }

            this.elements.paymentStatus.classList.remove('loading');

            const status = data.transaction_status || 'unknown';
            this.setStatusDisplay(status, data);

            if (status === 'pending') {
                this.setupAutoRefresh();
            } else {
                this.clearAutoRefresh();
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
        this.elements.paymentStatus.classList.remove('success', 'pending', 'failed', 'unknown', 'loading');

        switch (status) {
            case 'settlement':
            case 'capture':
            case 'paid':
            case 'success':
                this.elements.paymentStatus.classList.add('success');
                this.elements.statusIcon.className = 'status-icon';
                this.elements.statusIcon.innerHTML = '<i class="fas fa-check-circle"></i>';
                this.elements.statusText.textContent = 'Pembayaran Berhasil!';
                this.elements.statusDescription.innerHTML = `
                    <div style="font-size: 1.2rem; margin-bottom: 1rem;">
                        🎉 Selamat! Pembayaran Anda telah berhasil diproses.
                    </div>
                    <div>
                        Booking lapangan Anda sudah dikonfirmasi dan siap digunakan.
                        <br><strong>Terima kasih telah memilih LapangKuy!</strong>
                    </div>
                `;
                
                setTimeout(() => {
                    this.triggerSuccessCelebration();
                }, 800);
                break;

            case 'pending':
                this.elements.paymentStatus.classList.add('pending');
                this.elements.statusIcon.className = 'status-icon';
                this.elements.statusIcon.innerHTML = '<i class="fas fa-clock"></i>';
                this.elements.statusText.textContent = 'Menunggu Pembayaran';
                this.elements.statusDescription.innerHTML = `
                    <div style="display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 1rem;">
                        ⏳ <span>Pembayaran Anda sedang diproses</span>
                    </div>
                    <div style="font-size: 0.95rem; opacity: 0.9;">
                        Halaman ini akan otomatis terupdate ketika pembayaran berhasil.
                        <br>Biasanya membutuhkan waktu 1-5 menit.
                    </div>
                `;
                break;

            case 'deny':
            case 'cancel':
            case 'expire':
            case 'failed':
            case 'cancelled':
                this.elements.paymentStatus.classList.add('failed');
                this.elements.statusIcon.className = 'status-icon';
                this.elements.statusIcon.innerHTML = '<i class="fas fa-times-circle"></i>';
                this.elements.statusText.textContent = 'Pembayaran Gagal';
                this.elements.statusDescription.innerHTML = `
                    <div style="font-size: 1.1rem; margin-bottom: 1rem;">
                        ❌ ${data.message || 'Pembayaran tidak dapat diproses.'}
                    </div>
                    <div>
                        Silakan coba lagi atau gunakan metode pembayaran lain.
                        <br>Jika masalah berlanjut, hubungi customer service kami.
                    </div>
                `;
                break;

            default:
                this.elements.paymentStatus.classList.add('unknown');
                this.elements.statusIcon.className = 'status-icon';
                this.elements.statusIcon.innerHTML = '<i class="fas fa-question-circle"></i>';
                this.elements.statusText.textContent = 'Status Tidak Diketahui';
                this.elements.statusDescription.innerHTML = `
                    <div style="font-size: 1.1rem; margin-bottom: 1rem;">
                        ❓ ${data.message || 'Status pembayaran tidak dapat ditentukan.'}
                    </div>
                    <div>
                        Silakan hubungi customer service kami untuk bantuan lebih lanjut.
                    </div>
                `;
        }
    }

    triggerSuccessCelebration() {
        const confettiContainer = document.createElement('div');
        confettiContainer.className = 'confetti-container';
        document.body.appendChild(confettiContainer);

        for (let i = 0; i < 100; i++) {
            const confetti = document.createElement('div');
            confetti.className = 'confetti';
            confetti.style.left = Math.random() * 100 + '%';
            confetti.style.animationDelay = Math.random() * 3 + 's';
            confetti.style.animationDuration = (Math.random() * 3 + 2) + 's';
            confettiContainer.appendChild(confetti);
        }

        setTimeout(() => {
            if (confettiContainer.parentNode) {
                confettiContainer.remove();
            }
        }, 5000);
    }

    handleError(error) {
        this.retryCount++;
        
        if (this.retryCount < this.maxRetries) {
            setTimeout(() => {
                this.checkPaymentStatus();
            }, 2000 * this.retryCount);
            
            this.elements.statusDescription.textContent = `Mencoba kembali... (${this.retryCount}/${this.maxRetries})`;
        } else {
            this.elements.paymentStatus.classList.remove('loading');
            this.elements.paymentStatus.classList.add('unknown');
            this.elements.statusIcon.className = 'status-icon';
            this.elements.statusIcon.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
            this.elements.statusText.textContent = 'Koneksi Bermasalah';
            this.elements.statusDescription.textContent = 'Tidak dapat mengecek status pembayaran. Periksa koneksi internet Anda dan coba lagi.';
        }
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
</script>
@endsection
