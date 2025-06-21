/**
 * Payment History JavaScript
 * Handles payment history display and filtering
 */

document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const paymentContainer = document.getElementById('payment-container');
    const loadingSpinner = document.getElementById('loading-spinner');
    const noPaymentsMsg = document.getElementById('no-payments');
    const tabs = document.querySelectorAll('.payment-tabs .tab');
    let currentPage = 1;
    let currentFilter = 'all';
    
    // Load payments when page loads
    loadPayments(currentPage, currentFilter);
    
    // Setup tab event listeners
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('active'));
            
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Get filter and reload payments
            currentFilter = this.getAttribute('data-filter');
            currentPage = 1;
            loadPayments(currentPage, currentFilter);
        });
    });
    
    /**
     * Load payments via AJAX
     */
    function loadPayments(page, filter) {
        // Show loading spinner
        loadingSpinner.style.display = 'block';
        noPaymentsMsg.style.display = 'none';
        
        // Clear existing payments
        const paymentCards = paymentContainer.querySelectorAll('.payment-card');
        paymentCards.forEach(card => card.remove());
        
        // Make AJAX request to get payments
        fetch(`/api/payments?page=${page}&payment_status=${filter === 'all' ? '' : filter}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Hide loading spinner
                loadingSpinner.style.display = 'none';
                
                // Check if we have payments
                if (data.data.payments.data && data.data.payments.data.length > 0) {
                    // Render payments
                    data.data.payments.data.forEach(payment => {
                        paymentContainer.appendChild(createPaymentCard(payment));
                    });
                    
                    // Render pagination
                    renderPagination(data.data.payments);
                } else {
                    // Show no payments message
                    noPaymentsMsg.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error loading payments:', error);
                loadingSpinner.style.display = 'none';
                noPaymentsMsg.style.display = 'block';
            });
    }
    
    /**
     * Create payment card element
     */
    function createPaymentCard(payment) {
        const card = document.createElement('div');
        card.className = 'payment-card';
        
        // Determine status class and icon
        let statusClass, statusIcon;
        switch (payment.payment_status) {
            case 'paid':
                statusClass = 'success';
                statusIcon = 'check-circle';
                break;
            case 'pending':
                statusClass = 'warning';
                statusIcon = 'clock';
                break;
            case 'failed':
            case 'cancelled':
                statusClass = 'danger';
                statusIcon = 'times-circle';
                break;
            case 'expired':
                statusClass = 'secondary';
                statusIcon = 'calendar-times';
                break;
            default:
                statusClass = 'info';
                statusIcon = 'question-circle';
        }
        
        // Format date
        const bookingDate = new Date(payment.date);
        const formattedDate = bookingDate.toLocaleDateString('id-ID', {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
        
        // Format price
        const formattedPrice = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(payment.total_price);
        
        // Build card HTML
        card.innerHTML = `
            <div class="payment-card-header">
                <div class="booking-date">${formattedDate}</div>
                <div class="payment-status status-${statusClass}">
                    <i class="fas fa-${statusIcon}"></i>
                    ${payment.payment_status === 'paid' ? 'Lunas' : 
                      payment.payment_status === 'pending' ? 'Menunggu' :
                      payment.payment_status === 'failed' ? 'Gagal' :
                      payment.payment_status === 'expired' ? 'Kedaluarsa' : 'Tidak diketahui'}
                </div>
            </div>
            <div class="payment-card-body">
                <h5>${payment.field ? payment.field.name : 'Lapangan'}</h5>
                <div class="payment-details">
                    <div class="detail">
                        <i class="fas fa-calendar-alt"></i>
                        ${formattedDate}
                    </div>
                    <div class="detail">
                        <i class="fas fa-clock"></i>
                        ${payment.start_time} - ${payment.end_time}
                    </div>
                    <div class="detail">
                        <i class="fas fa-money-bill-wave"></i>
                        ${formattedPrice}
                    </div>
                </div>
            </div>
            <div class="payment-card-footer">
                <a href="/bookings/${payment.id}" class="btn btn-sm btn-primary">
                    <i class="fas fa-eye me-1"></i>Detail
                </a>
                ${payment.payment_status === 'pending' ? 
                    `<a href="/bookings/${payment.id}/payment" class="btn btn-sm btn-success">
                        <i class="fas fa-credit-card me-1"></i>Bayar
                    </a>` : ''}
            </div>
        `;
        
        return card;
    }
    
    /**
     * Render pagination
     */
    function renderPagination(paginationData) {
        const paginationContainer = document.getElementById('pagination-container');
        paginationContainer.innerHTML = '';
        
        if (!paginationData.last_page || paginationData.last_page <= 1) {
            return; // No need for pagination
        }
        
        const pagination = document.createElement('ul');
        pagination.className = 'pagination';
        
        // Previous button
        const prevLi = document.createElement('li');
        prevLi.className = `page-item ${paginationData.current_page === 1 ? 'disabled' : ''}`;
        const prevLink = document.createElement('a');
        prevLink.className = 'page-link';
        prevLink.href = '#';
        prevLink.innerHTML = '&laquo;';
        if (paginationData.current_page > 1) {
            prevLink.addEventListener('click', function(e) {
                e.preventDefault();
                currentPage = paginationData.current_page - 1;
                loadPayments(currentPage, currentFilter);
            });
        }
        prevLi.appendChild(prevLink);
        pagination.appendChild(prevLi);
        
        // Page numbers
        for (let i = 1; i <= paginationData.last_page; i++) {
            const li = document.createElement('li');
            li.className = `page-item ${i === paginationData.current_page ? 'active' : ''}`;
            
            const link = document.createElement('a');
            link.className = 'page-link';
            link.href = '#';
            link.textContent = i;
            
            if (i !== paginationData.current_page) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentPage = i;
                    loadPayments(currentPage, currentFilter);
                });
            }
            
            li.appendChild(link);
            pagination.appendChild(li);
        }
        
        // Next button
        const nextLi = document.createElement('li');
        nextLi.className = `page-item ${paginationData.current_page === paginationData.last_page ? 'disabled' : ''}`;
        const nextLink = document.createElement('a');
        nextLink.className = 'page-link';
        nextLink.href = '#';
        nextLink.innerHTML = '&raquo;';
        if (paginationData.current_page < paginationData.last_page) {
            nextLink.addEventListener('click', function(e) {
                e.preventDefault();
                currentPage = paginationData.current_page + 1;
                loadPayments(currentPage, currentFilter);
            });
        }
        nextLi.appendChild(nextLink);
        pagination.appendChild(nextLi);
        
        paginationContainer.appendChild(pagination);
    }
});
