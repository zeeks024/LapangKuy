@extends('layouts.app')

@section('title', 'Riwayat Pembayaran')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Riwayat Pembayaran</h2>
                <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Booking
                </a>
            </div>
            
            <!-- Filter tabs -->
            <div class="payment-tabs mb-4">
                <div class="tab active" data-filter="all">Semua</div>
                <div class="tab" data-filter="paid">Lunas</div>
                <div class="tab" data-filter="pending">Menunggu</div>
                <div class="tab" data-filter="failed">Gagal</div>
                <div class="tab" data-filter="expired">Kedaluarsa</div>
            </div>

            <!-- Payment List -->
            <div class="payment-list" id="payment-container">
                @if($payments->isEmpty())
                    <div class="no-payments-message text-center py-5">
                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                        <h4>Belum ada riwayat pembayaran</h4>
                        <p class="text-muted">Pembayaran Anda akan muncul di sini setelah Anda melakukan booking.</p>
                        <a href="{{ route('fields.index') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-search me-2"></i>Cari Lapangan
                        </a>
                    </div>
                @else
                    @foreach($payments as $payment)
                        <div class="payment-card">
                            <div class="payment-card-header">
                                <div class="booking-date">{{ date('d M Y', strtotime($payment->date)) }}</div>
                                <div class="payment-status status-{{ $payment->payment_status === 'paid' ? 'success' : ($payment->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                    <i class="fas fa-{{ $payment->payment_status === 'paid' ? 'check-circle' : ($payment->payment_status === 'pending' ? 'clock' : 'times-circle') }}"></i>
                                    {{ $payment->payment_status === 'paid' ? 'Lunas' : ($payment->payment_status === 'pending' ? 'Menunggu' : 'Gagal') }}
                                </div>
                            </div>
                            <div class="payment-card-body">
                                <h5>{{ $payment->field->name ?? 'Lapangan' }}</h5>
                                <div class="payment-details">
                                    <div class="detail">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ date('d M Y', strtotime($payment->date)) }}
                                    </div>
                                    <div class="detail">
                                        <i class="fas fa-clock"></i>
                                        {{ $payment->start_time }} - {{ $payment->end_time }}
                                    </div>
                                    <div class="detail">
                                        <i class="fas fa-money-bill-wave"></i>
                                        Rp {{ number_format($payment->total_price, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                            <div class="payment-card-footer">
                                <a href="{{ route('bookings.show', $payment->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye me-1"></i>Detail
                                </a>
                                @if($payment->payment_status === 'pending')
                                    <a href="{{ route('bookings.payment', $payment->id) }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-credit-card me-1"></i>Bayar
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
                
                <!-- Loading spinner for AJAX loading -->
                <div class="spinner-container text-center py-5" id="loading-spinner" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Mengambil data pembayaran...</p>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                <div id="pagination-container">
                    {{ $payments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Detail Modal -->
<div class="modal fade" id="paymentDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="payment-detail-spinner" class="text-center py-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div id="payment-detail-content" style="display: none;">
                    <!-- Payment details will be inserted here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .payment-tabs {
        display: flex;
        border-radius: 8px;
        overflow: hidden;
        background-color: #f8f9fa;
        margin-bottom: 20px;
    }
    
    .payment-tabs .tab {
        padding: 12px 20px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .payment-tabs .tab.active {
        background-color: var(--primary-color);
        color: white;
    }
    
    .payment-card {
        border-radius: 10px;
        border: 1px solid #eee;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }
    
    .payment-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    
    .payment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    
    .payment-date {
        color: #666;
        font-size: 0.9rem;
    }
    
    .payment-field {
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 5px;
    }
    
    .payment-info {
        color: #666;
        margin-bottom: 15px;
    }
    
    .payment-price {
        font-weight: 700;
        font-size: 1.2rem;
        color: var(--primary-color);
    }
    
    .payment-status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .payment-status.paid {
        background-color: #d4edda;
        color: #155724;
    }
    
    .payment-status.pending {
        background-color: #fff3cd;
        color: #856404;
    }
    
    .payment-status.failed {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .payment-status.expired {
        background-color: #e2e3e5;
        color: #383d41;
    }
    
    .payment-method {
        display: flex;
        align-items: center;
        font-size: 0.9rem;
        color: #666;
    }
    
    .payment-method i {
        margin-right: 5px;
    }
    
    .receipt-section {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
    }
    
    .receipt-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
    }
    
    .pagination {
        margin: 0;
    }
    
    .booking-time {
        display: inline-block;
        border-radius: 4px;
        background-color: #e9ecef;
        padding: 3px 8px;
        font-size: 0.85rem;
    }
    
    .view-details-btn {
        color: var(--primary-color);
        cursor: pointer;
        font-weight: 500;
    }
    
    .view-details-btn:hover {
        text-decoration: underline;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Current filter and page
    let currentFilter = 'all';
    let currentPage = 1;
    
    // Initial loading
    loadPayments(currentFilter, currentPage);
    
    // Tab filter event handlers
    document.querySelectorAll('.payment-tabs .tab').forEach(tab => {
        tab.addEventListener('click', function() {
            // Update active tab
            document.querySelectorAll('.payment-tabs .tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Update filter and reload
            currentFilter = this.getAttribute('data-filter');
            currentPage = 1;
            loadPayments(currentFilter, currentPage);
        });
    });
    
    // Function to load payments
    function loadPayments(filter, page) {
        const paymentContainer = document.getElementById('payment-container');
        const loadingSpinner = document.getElementById('loading-spinner');
        const noPaymentsMessage = document.getElementById('no-payments');
        
        // Show loading spinner
        loadingSpinner.style.display = 'block';
        noPaymentsMessage.style.display = 'none';
        
        // Clear previous payments
        const previousPayments = paymentContainer.querySelectorAll('.payment-card');
        previousPayments.forEach(card => card.remove());
        
        // Build query params
        let url = '/api/payments?page=' + page;
        if (filter !== 'all') {
            url += '&payment_status=' + filter;
        }
        
        // Fetch payments from API
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            // Hide spinner
            loadingSpinner.style.display = 'none';
            
            if (data.status === 'success') {
                const payments = data.data.payments.data;
                
                // Check if there are payments
                if (payments && payments.length > 0) {
                    // Render payments
                    payments.forEach(payment => {
                        const paymentCard = createPaymentCard(payment);
                        paymentContainer.appendChild(paymentCard);
                    });
                    
                    // Render pagination
                    renderPagination(data.data.payments.links, data.data.payments.current_page);
                } else {
                    // Show no payments message
                    noPaymentsMessage.style.display = 'block';
                    document.getElementById('pagination-container').innerHTML = '';
                }
            } else {
                // Show error message
                paymentContainer.innerHTML = '<div class="alert alert-danger">Gagal memuat data pembayaran. Silakan coba lagi.</div>';
            }
        })
        .catch(error => {
            loadingSpinner.style.display = 'none';
            paymentContainer.innerHTML = '<div class="alert alert-danger">Terjadi kesalahan jaringan. Silakan coba lagi.</div>';
            console.error('Error loading payments:', error);
        });
    }
    
    // Function to create payment card
    function createPaymentCard(payment) {
        const card = document.createElement('div');
        card.className = 'payment-card';
        
        // Format date
        const date = new Date(payment.created_at);
        const formattedDate = date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
        
        // Get payment status class
        const statusClass = payment.payment_status === 'paid' ? 'paid' : 
                           payment.payment_status === 'pending' ? 'pending' :
                           payment.payment_status === 'expired' ? 'expired' : 'failed';
        
        // Get payment method icon
        let methodIcon = 'fa-money-bill-wave';
        if (payment.payment_method && payment.payment_method.includes('bank')) {
            methodIcon = 'fa-university';
        } else if (payment.payment_method && payment.payment_method.includes('card')) {
            methodIcon = 'fa-credit-card';
        } else if (payment.payment_method && payment.payment_method.includes('ovo') || 
                  payment.payment_method && payment.payment_method.includes('gopay') ||
                  payment.payment_method && payment.payment_method.includes('dana')) {
            methodIcon = 'fa-mobile-alt';
        }
        
        card.innerHTML = `
            <div class="payment-header">
                <div>
                    <div class="payment-date">${formattedDate}</div>
                    <div class="payment-field">${payment.field ? payment.field.name : 'Unknown Field'}</div>
                </div>
                <div class="payment-status ${statusClass}">${getStatusLabel(payment.payment_status)}</div>
            </div>
            <div class="payment-info">
                <div class="d-flex justify-content-between mb-2">
                    <div class="booking-time">
                        <i class="far fa-calendar-alt me-1"></i>
                        ${formatDate(payment.date)}
                    </div>
                    <div class="booking-time">
                        <i class="far fa-clock me-1"></i>
                        ${payment.start_time} - ${payment.end_time}
                    </div>
                </div>
                ${payment.payment_method ? `
                <div class="payment-method mb-2">
                    <i class="fas ${methodIcon}"></i>
                    <span>${formatPaymentMethod(payment.payment_method)}</span>
                </div>
                ` : ''}
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <div class="payment-price">Rp ${formatNumber(payment.total_price)}</div>
                <div class="view-details-btn" data-id="${payment.id}">Lihat Detail</div>
            </div>
        `;
        
        // Add event listener to view-details button
        card.querySelector('.view-details-btn').addEventListener('click', function() {
            const paymentId = this.getAttribute('data-id');
            openPaymentDetail(paymentId);
        });
        
        return card;
    }
    
    // Function to render pagination
    function renderPagination(links, currentPage) {
        const paginationContainer = document.getElementById('pagination-container');
        paginationContainer.innerHTML = '';
        
        if (!links || links.length <= 3) return;
        
        const ul = document.createElement('ul');
        ul.className = 'pagination';
        
        links.forEach(link => {
            // Skip "previous" button if on first page and "next" button if on last page
            if ((link.label === '&laquo; Previous' && currentPage === 1) ||
                (link.label === 'Next &raquo;' && !link.url)) {
                return;
            }
            
            const li = document.createElement('li');
            li.className = `page-item ${link.active ? 'active' : ''}`;
            if (!link.url) li.classList.add('disabled');
            
            const a = document.createElement('a');
            a.className = 'page-link';
            a.innerHTML = link.label;
            
            if (link.url) {
                a.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Extract page number from URL
                    const url = new URL(link.url, window.location.origin);
                    currentPage = parseInt(url.searchParams.get('page')) || 1;
                    
                    loadPayments(currentFilter, currentPage);
                });
            }
            
            li.appendChild(a);
            ul.appendChild(li);
        });
        
        paginationContainer.appendChild(ul);
    }
    
    // Function to open payment detail modal
    function openPaymentDetail(paymentId) {
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('paymentDetailModal'));
        modal.show();
        
        // Show spinner and hide content
        document.getElementById('payment-detail-spinner').style.display = 'block';
        document.getElementById('payment-detail-content').style.display = 'none';
        
        // Fetch payment details
        fetch(`/api/payments/${paymentId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            // Hide spinner
            document.getElementById('payment-detail-spinner').style.display = 'none';
            
            if (data.status === 'success') {
                const payment = data.data.payment;
                const content = document.getElementById('payment-detail-content');
                
                // Fill content
                content.innerHTML = `
                    <div class="text-center mb-3">
                        <span class="payment-status ${payment.payment_status}">${getStatusLabel(payment.payment_status)}</span>
                    </div>
                    
                    <div class="receipt-section mb-3">
                        <div class="receipt-row">
                            <span>Kode Booking:</span>
                            <span><strong>${payment.booking_code || `LK${paymentId.toString().padStart(6, '0')}`}</strong></span>
                        </div>
                        <div class="receipt-row">
                            <span>Lapangan:</span>
                            <span>${payment.field_name}</span>
                        </div>
                        <div class="receipt-row">
                            <span>Lokasi:</span>
                            <span>${payment.field_location}</span>
                        </div>
                        <div class="receipt-row">
                            <span>Tanggal:</span>
                            <span>${formatDate(payment.date)}</span>
                        </div>
                        <div class="receipt-row">
                            <span>Waktu:</span>
                            <span>${payment.start_time} - ${payment.end_time}</span>
                        </div>
                    </div>
                    
                    <div class="receipt-section mb-3">
                        <div class="receipt-row">
                            <span>Total Pembayaran:</span>
                            <span class="payment-price">Rp ${formatNumber(payment.total_price)}</span>
                        </div>
                        <div class="receipt-row">
                            <span>Metode Pembayaran:</span>
                            <span>${payment.payment_method ? formatPaymentMethod(payment.payment_method) : 'N/A'}</span>
                        </div>
                        ${payment.transaction_id ? `
                        <div class="receipt-row">
                            <span>ID Transaksi:</span>
                            <span><code>${payment.transaction_id}</code></span>
                        </div>
                        ` : ''}
                        ${payment.payment_date ? `
                        <div class="receipt-row">
                            <span>Tanggal Pembayaran:</span>
                            <span>${formatDateTime(payment.payment_date)}</span>
                        </div>
                        ` : ''}
                    </div>
                    
                    ${payment.payment_status === 'paid' ? `
                    <div class="alert alert-success text-center">
                        <i class="fas fa-check-circle me-2"></i>
                        Pembayaran telah berhasil dikonfirmasi
                    </div>
                    ` : payment.payment_status === 'pending' ? `
                    <div class="d-grid">
                        <a href="/bookings/${paymentId}/payment" class="btn btn-primary">
                            <i class="fas fa-credit-card me-2"></i>Lanjutkan Pembayaran
                        </a>
                    </div>
                    ` : payment.payment_status === 'failed' ? `
                    <div class="alert alert-danger text-center">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Pembayaran gagal
                    </div>
                    <div class="d-grid">
                        <a href="/bookings/${paymentId}/payment" class="btn btn-danger">
                            <i class="fas fa-sync me-2"></i>Coba Lagi
                        </a>
                    </div>
                    ` : `
                    <div class="alert alert-secondary text-center">
                        <i class="fas fa-clock me-2"></i>
                        Pembayaran kedaluwarsa
                    </div>
                    `}
                `;
                
                // Show content
                content.style.display = 'block';
                
            } else {
                document.getElementById('payment-detail-content').innerHTML = `
                    <div class="alert alert-danger text-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Gagal memuat detail pembayaran
                    </div>
                `;
                document.getElementById('payment-detail-content').style.display = 'block';
            }
        })
        .catch(error => {
            document.getElementById('payment-detail-spinner').style.display = 'none';
            document.getElementById('payment-detail-content').innerHTML = `
                <div class="alert alert-danger text-center">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Terjadi kesalahan jaringan
                </div>
            `;
            document.getElementById('payment-detail-content').style.display = 'block';
            console.error('Error fetching payment details:', error);
        });
    }
    
    // Helper functions
    function getStatusLabel(status) {
        switch (status) {
            case 'paid': return 'Lunas';
            case 'pending': return 'Menunggu';
            case 'failed': return 'Gagal';
            case 'expired': return 'Kedaluwarsa';
            case 'refunded': return 'Dikembalikan';
            default: return status.charAt(0).toUpperCase() + status.slice(1);
        }
    }
    
    function formatPaymentMethod(method) {
        // Format payment method name
        if (!method) return 'N/A';
        
        if (method.includes('_')) {
            return method.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
        }
        
        return method.charAt(0).toUpperCase() + method.slice(1);
    }
    
    function formatDate(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    }
    
    function formatDateTime(dateTimeString) {
        if (!dateTimeString) return '-';
        const date = new Date(dateTimeString);
        return date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    
    function formatNumber(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }
});
</script>

<!-- Include payment history JS -->
<script src="{{ asset('assets/js/payment-history.js') }}"></script>

<!-- Include enhanced payment status JS (inspired by stunting project) -->
<script src="{{ asset('assets/js/enhanced-payment-status.js') }}"></script>
@endpush
