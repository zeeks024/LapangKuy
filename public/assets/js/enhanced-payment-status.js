/**
 * Enhanced Payment Status Management (inspired by stunting project)
 * Real-time payment status checking and auto-refresh functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize enhanced payment features
    initializePaymentStatusChecker();
    addLoadingStates();
    addAutoRefresh();
    enhanceSearchFunctionality();
    addPaymentMethodTooltips();
    addCopyFunctionality();
});

function initializePaymentStatusChecker() {
    console.log('Initializing enhanced payment status checker...');
    
    // Check for pending payments on page load
    checkAllPendingPayments();
    
    // Add click handlers for payment detail buttons
    document.querySelectorAll('[data-payment-detail]').forEach(button => {
        button.addEventListener('click', function() {
            const bookingId = this.getAttribute('data-payment-detail');
            showPaymentDetail(bookingId);
        });
    });
}

function checkAllPendingPayments() {
    const pendingCards = document.querySelectorAll('.payment-card[data-status="pending"]');
    
    pendingCards.forEach(card => {
        const orderId = card.getAttribute('data-order-id');
        if (orderId) {
            checkSinglePaymentStatus(orderId, card);
        }
    });
}

function checkSinglePaymentStatus(orderId, cardElement) {
    fetch('/check-payment-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ order_id: orderId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.transaction_status !== 'pending') {
            updatePaymentCard(cardElement, data);
        }
    })
    .catch(error => {
        console.log('Error checking payment status:', error);
    });
}

function updatePaymentCard(cardElement, paymentData) {
    const statusElement = cardElement.querySelector('.payment-status');
    const actionButtonsElement = cardElement.querySelector('.payment-actions');
    
    if (statusElement) {
        // Update status with new data
        const newStatus = mapTransactionStatus(paymentData.transaction_status);
        statusElement.className = `payment-status status-${newStatus.class}`;
        statusElement.innerHTML = `
            <i class="fas fa-${newStatus.icon}"></i>
            ${newStatus.label}
        `;
        
        // Add update animation
        statusElement.style.animation = 'pulse 0.5s ease';
        setTimeout(() => {
            statusElement.style.animation = '';
        }, 500);
    }
    
    // Remove payment button if completed
    if (['paid', 'settlement', 'capture'].includes(paymentData.transaction_status)) {
        const payButton = cardElement.querySelector('.btn-pay');
        if (payButton) {
            payButton.style.display = 'none';
        }
        
        // Add success badge
        if (actionButtonsElement && !actionButtonsElement.querySelector('.success-badge')) {
            const successBadge = document.createElement('span');
            successBadge.className = 'badge bg-success success-badge';
            successBadge.innerHTML = '<i class="fas fa-check me-1"></i>Pembayaran Berhasil';
            actionButtonsElement.appendChild(successBadge);
        }
    }
    
    // Update data attributes
    cardElement.setAttribute('data-status', paymentData.transaction_status);
}

function mapTransactionStatus(status) {
    const statusMap = {
        'settlement': { class: 'success', icon: 'check-circle', label: 'Lunas' },
        'capture': { class: 'success', icon: 'check-circle', label: 'Lunas' },
        'paid': { class: 'success', icon: 'check-circle', label: 'Lunas' },
        'pending': { class: 'warning', icon: 'clock', label: 'Menunggu' },
        'deny': { class: 'danger', icon: 'times-circle', label: 'Ditolak' },
        'cancel': { class: 'danger', icon: 'times-circle', label: 'Dibatalkan' },
        'expire': { class: 'danger', icon: 'clock', label: 'Kedaluwarsa' },
        'failed': { class: 'danger', icon: 'exclamation-triangle', label: 'Gagal' },
        'error': { class: 'danger', icon: 'exclamation-triangle', label: 'Error' }
    };
    
    return statusMap[status] || { class: 'secondary', icon: 'question-circle', label: 'Unknown' };
}

function showPaymentDetail(bookingId) {
    // Show loading in modal
    const modal = document.getElementById('paymentDetailModal');
    const modalBody = modal.querySelector('.modal-body');
    
    modalBody.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Memuat detail pembayaran...</p>
        </div>
    `;
    
    // Show modal
    const modalInstance = new bootstrap.Modal(modal);
    modalInstance.show();
    
    // Fetch payment details
    fetch(`/payment-detail/${bookingId}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderPaymentDetail(data.payment, modalBody);
        } else {
            showPaymentDetailError(modalBody, data.message);
        }
    })
    .catch(error => {
        console.error('Error fetching payment details:', error);
        showPaymentDetailError(modalBody, 'Terjadi kesalahan jaringan');
    });
}

function renderPaymentDetail(payment, container) {
    const statusInfo = mapTransactionStatus(payment.payment_status);
    
    container.innerHTML = `
        <div class="payment-detail-content">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h6 class="text-muted">ID Pesanan</h6>
                    <p class="mb-0"><code>${payment.order_id}</code></p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Status Pembayaran</h6>
                    <span class="badge bg-${statusInfo.class}">
                        <i class="fas fa-${statusInfo.icon} me-1"></i>${statusInfo.label}
                    </span>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <h6 class="text-muted">Lapangan</h6>
                    <p class="mb-0">${payment.field_name}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Tanggal Booking</h6>
                    <p class="mb-0">${payment.date}</p>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <h6 class="text-muted">Waktu</h6>
                    <p class="mb-0">${payment.start_time} - ${payment.end_time}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Total Pembayaran</h6>
                    <p class="mb-0 fw-bold text-primary">Rp ${formatNumber(payment.total_price)}</p>
                </div>
            </div>
            
            ${payment.payment_method ? `
            <div class="row mb-3">
                <div class="col-md-6">
                    <h6 class="text-muted">Metode Pembayaran</h6>
                    <p class="mb-0">${formatPaymentMethod(payment.payment_method)}</p>
                </div>
                ${payment.payment_date ? `
                <div class="col-md-6">
                    <h6 class="text-muted">Tanggal Pembayaran</h6>
                    <p class="mb-0">${payment.payment_date}</p>
                </div>
                ` : ''}
            </div>
            ` : ''}
            
            ${payment.transaction_id ? `
            <div class="row mb-3">
                <div class="col-12">
                    <h6 class="text-muted">ID Transaksi</h6>
                    <p class="mb-0"><code>${payment.transaction_id}</code></p>
                </div>
            </div>
            ` : ''}
            
            ${payment.payment_status === 'paid' ? `
            <div class="alert alert-success text-center">
                <i class="fas fa-check-circle me-2"></i>
                Pembayaran telah berhasil dikonfirmasi
            </div>
            ` : payment.payment_status === 'pending' ? `
            <div class="d-grid">
                <a href="/bookings/${payment.id}/payment" class="btn btn-primary">
                    <i class="fas fa-credit-card me-2"></i>Lanjutkan Pembayaran
                </a>
            </div>
            ` : ''}
        </div>
    `;
}

function showPaymentDetailError(container, message) {
    container.innerHTML = `
        <div class="alert alert-danger text-center">
            <i class="fas fa-exclamation-triangle me-2"></i>
            ${message}
        </div>
    `;
}

function addLoadingStates() {
    const buttons = document.querySelectorAll('.btn-pay, .btn-detail');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!this.classList.contains('loading')) {
                this.classList.add('loading');
                
                const originalContent = this.innerHTML;
                this.setAttribute('data-original', originalContent);
                
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...';
                
                setTimeout(() => {
                    this.classList.remove('loading');
                    this.innerHTML = originalContent;
                }, 2000);
            }
        });
    });
}

function addAutoRefresh() {
    const pendingPayments = document.querySelectorAll('.payment-card[data-status="pending"]');
    
    if (pendingPayments.length > 0) {
        console.log(`Found ${pendingPayments.length} pending payments. Starting auto-refresh...`);
        
        // Check for updates every 30 seconds
        setInterval(() => {
            checkAllPendingPayments();
        }, 30000);
    }
}

function enhanceSearchFunctionality() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                filterPayments();
            }, 300);
        });
    }
}

function filterPayments() {
    const searchInput = document.getElementById('searchInput');
    const activeTab = document.querySelector('.nav-link.active');
    
    if (!searchInput || !activeTab) return;
    
    const searchTerm = searchInput.value.toLowerCase();
    const filterStatus = activeTab.getAttribute('data-status');
    
    document.querySelectorAll('.payment-card').forEach(card => {
        const cardStatus = card.getAttribute('data-status');
        const fieldName = card.querySelector('.payment-card h5')?.textContent?.toLowerCase() || '';
        const orderId = card.getAttribute('data-order-id')?.toLowerCase() || '';
        
        const statusMatch = filterStatus === 'all' || cardStatus === filterStatus;
        const searchMatch = fieldName.includes(searchTerm) || orderId.includes(searchTerm);
        
        if (statusMatch && searchMatch) {
            card.style.display = '';
            card.style.animation = 'fadeIn 0.3s ease';
        } else {
            card.style.display = 'none';
        }
    });
}

function addPaymentMethodTooltips() {
    const paymentMethods = document.querySelectorAll('[data-payment-method]');
    paymentMethods.forEach(method => {
        const tooltip = new bootstrap.Tooltip(method, {
            title: getPaymentMethodInfo(method.dataset.paymentMethod)
        });
    });
}

function getPaymentMethodInfo(method) {
    const info = {
        'credit_card': 'Pembayaran menggunakan kartu kredit Visa/Mastercard',
        'bca_va': 'Transfer melalui Virtual Account BCA',
        'bni_va': 'Transfer melalui Virtual Account BNI',
        'bri_va': 'Transfer melalui Virtual Account BRI',
        'mandiri_va': 'Transfer melalui Virtual Account Mandiri',
        'gopay': 'Pembayaran digital melalui aplikasi GoPay',
        'shopeepay': 'Pembayaran digital melalui ShopeePay',
        'ovo': 'Pembayaran digital melalui aplikasi OVO',
        'dana': 'Pembayaran digital melalui aplikasi DANA'
    };
    return info[method] || 'Metode pembayaran digital';
}

function addCopyFunctionality() {
    const copyButtons = document.querySelectorAll('[data-copy]');
    copyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const textToCopy = this.dataset.copy;
            navigator.clipboard.writeText(textToCopy).then(() => {
                showCopySuccess(this);
            }).catch(() => {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = textToCopy;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showCopySuccess(this);
            });
        });
    });
}

function showCopySuccess(button) {
    const originalText = button.textContent;
    button.textContent = 'Copied!';
    button.classList.add('btn-success');
    
    setTimeout(() => {
        button.textContent = originalText;
        button.classList.remove('btn-success');
    }, 2000);
}

// Utility functions
function formatNumber(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

function formatPaymentMethod(method) {
    const methods = {
        'credit_card': 'Kartu Kredit',
        'bca_va': 'Virtual Account BCA',
        'bni_va': 'Virtual Account BNI',
        'bri_va': 'Virtual Account BRI',
        'mandiri_va': 'Virtual Account Mandiri',
        'gopay': 'GoPay',
        'shopeepay': 'ShopeePay',
        'ovo': 'OVO',
        'dana': 'DANA'
    };
    return methods[method] || method;
}

function formatDateTime(dateTime) {
    return new Date(dateTime).toLocaleString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .payment-card {
        transition: all 0.3s ease;
    }
    
    .payment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .success-badge {
        animation: fadeIn 0.5s ease;
    }
`;
document.head.appendChild(style);
