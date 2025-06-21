@extends('layouts.app')
@section('title', 'Booking Saya')
@section('content')
<div class="container fade-in" style="max-width:1200px; margin:40px auto;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="section-title mb-0">Booking Saya</h1>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-success" onclick="exportToExcel()">
                <i class="fas fa-file-excel me-2"></i>Export Excel
            </button>
            <button class="btn btn-outline-info" onclick="printBookings()">
                <i class="fas fa-print me-2"></i>Print
            </button>
            <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
            <a href="{{ route('bookings.payments') }}" class="btn btn-primary">
                <i class="fas fa-credit-card me-2"></i>Lihat Riwayat Pembayaran
            </a>
        </div>
    </div>
    
    <!-- Search and Filter -->
    <div class="search-filter-section mb-4">
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" class="form-control" id="searchBooking" placeholder="Cari berdasarkan nama lapangan, kode booking...">
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <select class="form-select" id="statusFilter">
                    <option value="">Semua Status</option>
                    <option value="pending">Menunggu</option>
                    <option value="confirmed">Dikonfirmasi</option>
                    <option value="completed">Selesai</option>
                    <option value="cancelled">Dibatalkan</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <input type="date" class="form-control" id="dateFilter" placeholder="Filter tanggal">
            </div>
        </div>
    </div>
    
    <div class="booking-tabs">
        <div class="tab active" data-tab="upcoming">Akan Datang</div>
        <div class="tab" data-tab="completed">Selesai</div>
        <div class="tab" data-tab="cancelled">Dibatalkan</div>
    </div>
    
    <div class="booking-list-container">
        @if(count($bookings) > 0)
            <div class="booking-list">
                @foreach($bookings as $booking)
                <div class="booking-card" data-status="{{ strtolower($booking->status) }}">
                    <div class="booking-left">
                        <div class="booking-image">
                            <img src="{{ $booking->field_id ? 'https://placehold.co/400x200/043E03/FFFFFF?text=Lapangan+'.$booking->field_id : 'https://placehold.co/400x200/043E03/FFFFFF?text=Lapangan' }}" alt="Lapangan">
                        </div>
                        <div class="booking-status {{ strtolower($booking->status) }}">
                            {{ ucfirst($booking->status) }}
                        </div>
                    </div>
                    
                    <div class="booking-details">
                        <div class="booking-header">
                            <h2 class="booking-title">{{ $booking->field_name }}</h2>
                            <div class="booking-id">ID: {{ $booking->booking_code ?? 'LK'.strtoupper(substr(md5($booking->id), 0, 8)) }}</div>
                        </div>
                        
                        <div class="booking-meta">
                            <div class="booking-meta-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span>{{ date('d M Y', strtotime($booking->date)) }}</span>
                            </div>
                            <div class="booking-meta-item">
                                <i class="fas fa-clock"></i>
                                <span>{{ $booking->start_time }} - {{ $booking->end_time }}</span>
                            </div>
                            <div class="booking-meta-item">
                                <i class="fas fa-hourglass-half"></i>
                                <span>{{ $booking->duration }} jam</span>
                            </div>
                        </div>
                        
                        <div class="booking-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $booking->field_location ?? 'Jakarta' }}</span>
                        </div>
                        
                        <div class="booking-price">
                            <div class="price-label">Total Pembayaran:</div>
                            <div class="price-amount">Rp{{ number_format($booking->total_price,0,',','.') }}</div>
                        </div>
                        
                        <div class="booking-payment-status {{ strtolower($booking->payment_status) }}">
                            {{ ucfirst($booking->payment_status) }}
                        </div>
                          <div class="booking-actions">
                            <a href="{{ route('bookings.show', $booking->id) }}" class="booking-action view">
                                <i class="fas fa-eye me-1"></i>Detail
                            </a>
                            
                            @if($booking->status == 'pending')
                                <a href="{{ route('bookings.edit', $booking->id) }}" class="booking-action edit">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                                <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST" class="cancel-form d-inline">
                                    @csrf
                                    <button type="submit" class="booking-action cancel" onclick="return confirm('Yakin ingin membatalkan booking ini?')">
                                        <i class="fas fa-times me-1"></i>Batalkan
                                    </button>
                                </form>
                            @endif
                            
                            @if($booking->status == 'confirmed')
                                <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST" class="cancel-form d-inline">
                                    @csrf
                                    <button type="submit" class="booking-action cancel" onclick="return confirm('Yakin ingin membatalkan booking ini?')">
                                        <i class="fas fa-times me-1"></i>Batalkan
                                    </button>
                                </form>
                            @endif
                            
                            @if($booking->status == 'completed')
                                <a href="{{ route('reviews.create', $booking->id) }}" class="booking-action review">
                                    <i class="fas fa-star me-1"></i>Beri Ulasan
                                </a>
                                <a href="{{ route('bookings.create', $booking->field_id) }}" class="booking-action rebook">
                                    <i class="fas fa-redo me-1"></i>Booking Lagi
                                </a>
                            @endif
                            
                            @if($booking->payment_status == 'unpaid' && $booking->status != 'cancelled')
                                <a href="{{ route('bookings.payment', $booking->id) }}" class="booking-action payment">
                                    <i class="fas fa-credit-card me-1"></i>Bayar
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="pagination">
                {{ $bookings->links() }}
            </div>
        @else
            <div class="no-bookings">
                <div class="no-data-icon">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <h3>Tidak Ada Booking</h3>
                <p>Anda belum memiliki booking lapangan.</p>
                <a href="{{ route('fields.index') }}" class="btn btn-primary">Cari Lapangan</a>
            </div>
        @endif
    </div>
</div>

<style>
    .booking-tabs {
        display: flex;
        margin-bottom: 30px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .tab {
        padding: 15px 30px;
        font-weight: 500;
        cursor: pointer;
        position: relative;
        color: var(--light-text);
        transition: var(--transition);
    }
    
    .tab.active {
        color: var(--primary-color);
    }
    
    .tab.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 3px;
        background-color: var(--primary-color);
    }
    
    .booking-list-container {
        background: var(--white);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        padding: 20px;
    }
    
    .booking-card {
        display: flex;
        border-bottom: 1px solid var(--border-color);
        padding: 20px 0;
    }
    
    .booking-card:last-child {
        border-bottom: none;
    }
    
    .booking-left {
        position: relative;
        margin-right: 20px;
    }
    
    .booking-image {
        width: 150px;
        height: 100px;
        border-radius: var(--radius-sm);
        overflow: hidden;
    }
    
    .booking-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .booking-status {
        position: absolute;
        top: -10px;
        right: -10px;
        padding: 5px 10px;
        border-radius: var(--radius-sm);
        font-size: 12px;
        font-weight: 500;
        color: white;
    }
    
    .booking-status.confirmed,
    .booking-status.completed {
        background-color: #28a745;
    }
    
    .booking-status.pending {
        background-color: #ffc107;
        color: #212529;
    }
    
    .booking-status.cancelled {
        background-color: #dc3545;
    }
    
    .booking-details {
        flex: 1;
    }
    
    .booking-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 10px;
    }
    
    .booking-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-color);
        margin: 0;
    }
    
    .booking-id {
        font-size: 12px;
        color: var(--light-text);
    }
    
    .booking-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 10px;
    }
    
    .booking-meta-item {
        display: flex;
        align-items: center;
    }
    
    .booking-meta-item i {
        margin-right: 5px;
        color: var(--primary-color);
    }
    
    .booking-location {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .booking-location i {
        margin-right: 5px;
        color: var(--primary-color);
    }
    
    .booking-price {
        display: flex;
        align-items: baseline;
        margin-bottom: 10px;
    }
    
    .price-label {
        margin-right: 5px;
        color: var(--light-text);
    }
    
    .price-amount {
        font-weight: 600;
        color: var(--primary-color);
    }
    
    .booking-payment-status {
        display: inline-block;
        padding: 3px 10px;
        border-radius: var(--radius-sm);
        font-size: 12px;
        margin-bottom: 15px;
    }
    
    .booking-payment-status.paid {
        background-color: #e6f2e6;
        color: #28a745;
    }
    
    .booking-payment-status.unpaid {
        background-color: #fff4e5;
        color: #fd7e14;
    }
    
    .booking-payment-status.refunded {
        background-color: #e5f6fd;
        color: #17a2b8;
    }
    
    .booking-actions {
        display: flex;
        gap: 10px;
    }
    
    .booking-action {
        padding: 8px 15px;
        border-radius: var(--radius-sm);
        font-size: 14px;
        font-weight: 500;
        text-align: center;
        cursor: pointer;
        transition: var(--transition);
    }
    
    .booking-action.view {
        background-color: var(--primary-color);
        color: white;
    }
    
    .booking-action.view:hover {
        background-color: #032B02;
    }
    
    .booking-action.cancel {
        background-color: #fff4f4;
        color: #dc3545;
        border: none;
    }
    
    .booking-action.cancel:hover {
        background-color: #ffe6e6;
    }
    
    .booking-action.review {
        background-color: #e6f2e6;
        color: var(--primary-color);
    }
    
    .booking-action.review:hover {
        background-color: #d7ead7;
    }
    
    .no-bookings {
        text-align: center;
        padding: 50px 0;
    }
    
    .no-data-icon {
        font-size: 60px;
        color: #ccc;
        margin-bottom: 20px;
    }
    
    .no-bookings h3 {
        font-size: 20px;
        margin-bottom: 10px;
    }
    
    .no-bookings p {
        color: var(--light-text);
        margin-bottom: 20px;
    }
    
    .cancel-form {
        display: inline;
    }
    
    .search-filter-section {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        margin-bottom: 1.5rem;
    }
    
    .search-box {
        position: relative;
    }
    
    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
    
    .search-box input {
        padding-left: 45px;
        border-radius: 8px;
        border: 1px solid #e3e6f0;
    }
    
    .form-select {
        border-radius: 8px;
        border: 1px solid #e3e6f0;
    }
    
    @media (max-width: 768px) {
        .booking-card {
            flex-direction: column;
        }
        
        .booking-left {
            margin-right: 0;
            margin-bottom: 15px;
        }
        
        .booking-image {
            width: 100%;
            height: 150px;
        }
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchBooking');
        const statusFilter = document.getElementById('statusFilter');
        const dateFilter = document.getElementById('dateFilter');
        const bookingCards = document.querySelectorAll('.booking-card');
        
        function filterBookings() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value.toLowerCase();
            const dateValue = dateFilter.value;
            
            bookingCards.forEach(card => {
                const fieldName = card.querySelector('.booking-title').textContent.toLowerCase();
                const bookingId = card.querySelector('.booking-id').textContent.toLowerCase();
                const status = card.dataset.status;
                const bookingDate = card.querySelector('.booking-meta-item span').textContent;
                
                let showCard = true;
                
                // Search filter
                if (searchTerm && !fieldName.includes(searchTerm) && !bookingId.includes(searchTerm)) {
                    showCard = false;
                }
                
                // Status filter
                if (statusValue && status !== statusValue) {
                    showCard = false;
                }
                
                // Date filter
                if (dateValue && !bookingDate.includes(dateValue)) {
                    showCard = false;
                }
                
                card.style.display = showCard ? 'block' : 'none';
            });
        }
        
        // Add event listeners
        searchInput.addEventListener('input', filterBookings);
        statusFilter.addEventListener('change', filterBookings);
        dateFilter.addEventListener('change', filterBookings);
        
        // Tab functionality
        const tabs = document.querySelectorAll('.tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                const tabType = this.dataset.tab;
                bookingCards.forEach(card => {
                    const status = card.dataset.status;
                    let showCard = false;
                    
                    switch(tabType) {
                        case 'upcoming':
                            showCard = ['pending', 'confirmed'].includes(status);
                            break;
                        case 'completed':
                            showCard = status === 'completed';
                            break;
                        case 'cancelled':
                            showCard = status === 'cancelled';
                            break;
                    }
                    
                    card.style.display = showCard ? 'block' : 'none';                });
            });
        });
    });

    // Export to Excel functionality
    function exportToExcel() {
        const bookings = [];
        document.querySelectorAll('.booking-card:not([style*="display: none"])').forEach(card => {
            const fieldName = card.querySelector('.booking-title').textContent;
            const bookingId = card.querySelector('.booking-id').textContent;
            const date = card.querySelector('.booking-meta-item span').textContent;
            const time = card.querySelectorAll('.booking-meta-item span')[1].textContent;
            const duration = card.querySelectorAll('.booking-meta-item span')[2].textContent;
            const status = card.dataset.status;
            const price = card.querySelector('.price-amount').textContent;
            
            bookings.push({
                'ID Booking': bookingId,
                'Nama Lapangan': fieldName,
                'Tanggal': date,
                'Waktu': time,
                'Durasi': duration,
                'Status': status,
                'Total': price
            });
        });
        
        // Convert to CSV and download
        const csv = convertToCSV(bookings);
        downloadCSV(csv, 'riwayat-booking.csv');
    }

    function convertToCSV(data) {
        if (!data.length) return '';
        
        const headers = Object.keys(data[0]);
        const csvHeaders = headers.join(',');
        const csvRows = data.map(row => 
            headers.map(header => `"${row[header]}"`).join(',')
        );
        
        return [csvHeaders, ...csvRows].join('\n');
    }

    function downloadCSV(csv, filename) {
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // Print functionality
    function printBookings() {
        const printContent = `
            <html>
            <head>
                <title>Riwayat Booking</title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    .header { text-align: center; margin-bottom: 30px; }
                    .booking-item { margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; }
                    .booking-title { font-weight: bold; font-size: 16px; }
                    .booking-meta { margin-top: 10px; }
                    .status { padding: 5px 10px; border-radius: 5px; color: white; }
                    .confirmed { background-color: #28a745; }
                    .pending { background-color: #ffc107; }
                    .cancelled { background-color: #dc3545; }
                    .completed { background-color: #17a2b8; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>Riwayat Booking</h1>
                    <p>Tanggal Cetak: ${new Date().toLocaleDateString('id-ID')}</p>
                </div>
        `;
        
        let bookingItems = '';
        document.querySelectorAll('.booking-card:not([style*="display: none"])').forEach(card => {
            const fieldName = card.querySelector('.booking-title').textContent;
            const bookingId = card.querySelector('.booking-id').textContent;
            const date = card.querySelector('.booking-meta-item span').textContent;
            const time = card.querySelectorAll('.booking-meta-item span')[1].textContent;
            const duration = card.querySelectorAll('.booking-meta-item span')[2].textContent;
            const status = card.dataset.status;
            const price = card.querySelector('.price-amount').textContent;
            
            bookingItems += `
                <div class="booking-item">
                    <div class="booking-title">${fieldName}</div>
                    <div class="booking-meta">
                        <p><strong>ID:</strong> ${bookingId}</p>
                        <p><strong>Tanggal:</strong> ${date}</p>
                        <p><strong>Waktu:</strong> ${time}</p>
                        <p><strong>Durasi:</strong> ${duration}</p>
                        <p><strong>Status:</strong> <span class="status ${status}">${status}</span></p>
                        <p><strong>Total:</strong> ${price}</p>
                    </div>
                </div>
            `;
        });
        
        const fullContent = printContent + bookingItems + '</body></html>';
        
        const printWindow = window.open('', '_blank');
        printWindow.document.write(fullContent);
        printWindow.document.close();
        printWindow.print();
    }
</script>
@endpush
@endsection
