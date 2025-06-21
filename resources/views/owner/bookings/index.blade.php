@extends('layouts.app')

@section('title', 'Kelola Booking')

@push('styles')
<style>
    .booking-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
    }
    
    .booking-stats {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    }
    
    .stat-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 8px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        border-left: 4px solid;
    }
    
    .stat-card.pending {
        border-left-color: #ffc107;
    }
    
    .stat-card.confirmed {
        border-left-color: #28a745;
    }
    
    .stat-card.cancelled {
        border-left-color: #dc3545;
    }
    
    .stat-card.completed {
        border-left-color: #17a2b8;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: #6c757d;
        font-size: 0.875rem;
        margin-bottom: 0;
    }
    
    .booking-filters {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    }
    
    .filter-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }
    
    .filter-tab {
        padding: 0.75rem 1.5rem;
        border: 2px solid #e9ecef;
        border-radius: 25px;
        background: white;
        color: #6c757d;
        text-decoration: none;
        transition: all 0.3s ease;
        font-weight: 500;
        cursor: pointer;
    }
    
    .filter-tab.active, .filter-tab:hover {
        border-color: #007bff;
        background: #007bff;
        color: white;
        text-decoration: none;
    }
    
    .booking-table-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    .enhanced-table {
        margin-bottom: 0;
    }
    
    .enhanced-table th {
        background: #f8f9fa;
        border: none;
        padding: 1rem;
        font-weight: 600;
        color: #495057;
    }
    
    .enhanced-table td {
        padding: 1rem;
        border: none;
        vertical-align: middle;
    }
    
    .enhanced-table tbody tr {
        border-bottom: 1px solid #f1f3f4;
        transition: all 0.3s ease;
    }
    
    .enhanced-table tbody tr:hover {
        background: #f8f9fa;
    }
    
    .booking-card {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
    }
    
    .booking-card:hover {
        border-color: #007bff;
        box-shadow: 0 2px 8px rgba(0,123,255,0.1);
    }
    
    .booking-id {
        font-weight: bold;
        color: #007bff;
    }
    
    .customer-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .customer-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 0.875rem;
    }
    
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-pending {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }
    
    .status-confirmed {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .status-completed {
        background: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }
    
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .action-btn {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .action-btn.confirm {
        background: #28a745;
        color: white;
    }
    
    .action-btn.confirm:hover {
        background: #218838;
        color: white;
    }
    
    .action-btn.reject {
        background: #dc3545;
        color: white;
    }
    
    .action-btn.reject:hover {
        background: #c82333;
        color: white;
    }
    
    .action-btn.view {
        background: #007bff;
        color: white;
    }
    
    .action-btn.view:hover {
        background: #0056b3;
        color: white;
    }
    
    .action-btn.contact {
        background: #17a2b8;
        color: white;
    }
    
    .action-btn.contact:hover {
        background: #138496;
        color: white;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .search-section {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: end;
    }
    
    .search-field {
        flex: 1;
        min-width: 200px;
    }
    
    .quick-actions {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    }
    
    .quick-action-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
        margin-right: 0.75rem;
        margin-bottom: 0.75rem;
    }
    
    .quick-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .booking-details-modal .modal-body {
        padding: 2rem;
    }
    
    .detail-section {
        margin-bottom: 2rem;
    }
    
    .detail-section h6 {
        color: #007bff;
        margin-bottom: 1rem;
        font-weight: 600;
    }
    
    .detail-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f3f4;
    }
    
    .detail-item:last-child {
        border-bottom: none;
    }
    
    .detail-label {
        font-weight: 500;
        color: #6c757d;
    }
    
    .detail-value {
        font-weight: 600;
        color: #495057;
    }
    
    @media (max-width: 768px) {
        .stat-card {
            margin-bottom: 1rem;
        }
        
        .filter-tabs {
            justify-content: center;
        }
        
        .search-section {
            flex-direction: column;
        }
        
        .search-field {
            min-width: auto;
        }
        
        .enhanced-table {
            font-size: 0.875rem;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .quick-action-btn {
            margin-right: 0;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="booking-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-2">Kelola Booking</h1>
                <p class="mb-0 opacity-75">Manage dan track semua booking untuk lapangan Anda</p>
            </div>
            <a href="{{ route('owner.dashboard') }}" class="btn btn-light">
                <i class="fas fa-arrow-left me-2"></i>Dashboard
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="booking-stats">
        <div class="row g-3">
            <div class="col-lg-3 col-md-6">
                <div class="stat-card pending">
                    <div class="stat-number text-warning">{{ $bookings->where('status', 'pending')->count() }}</div>
                    <div class="stat-label">Menunggu Konfirmasi</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card confirmed">
                    <div class="stat-number text-success">{{ $bookings->where('status', 'confirmed')->count() }}</div>
                    <div class="stat-label">Dikonfirmasi</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card completed">
                    <div class="stat-number text-info">{{ $bookings->where('status', 'completed')->count() }}</div>
                    <div class="stat-label">Selesai</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card cancelled">
                    <div class="stat-number text-danger">{{ $bookings->where('status', 'cancelled')->count() }}</div>
                    <div class="stat-label">Dibatalkan</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h5 class="mb-3">Quick Actions</h5>
        <a href="{{ route('owner.fields') }}" class="quick-action-btn">
            <i class="fas fa-futbol"></i>Kelola Lapangan
        </a>
        <a href="{{ route('owner.fields.create') }}" class="quick-action-btn">
            <i class="fas fa-plus"></i>Tambah Lapangan
        </a>
        <button class="quick-action-btn" onclick="exportBookings()">
            <i class="fas fa-download"></i>Export Data
        </button>
        <button class="quick-action-btn" onclick="viewReports()">
            <i class="fas fa-chart-bar"></i>Lihat Laporan
        </button>
    </div>

    <!-- Filters & Search -->
    <div class="booking-filters">
        <div class="row">
            <div class="col-lg-8">
                <h5 class="mb-3">Filter Booking</h5>
                <div class="filter-tabs">
                    <div class="filter-tab active" data-status="all">
                        Semua <span class="badge bg-light text-dark ms-1">{{ $bookings->count() }}</span>
                    </div>
                    <div class="filter-tab" data-status="pending">
                        Menunggu <span class="badge bg-warning ms-1">{{ $bookings->where('status', 'pending')->count() }}</span>
                    </div>
                    <div class="filter-tab" data-status="confirmed">
                        Dikonfirmasi <span class="badge bg-success ms-1">{{ $bookings->where('status', 'confirmed')->count() }}</span>
                    </div>
                    <div class="filter-tab" data-status="completed">
                        Selesai <span class="badge bg-info ms-1">{{ $bookings->where('status', 'completed')->count() }}</span>
                    </div>
                    <div class="filter-tab" data-status="cancelled">
                        Dibatalkan <span class="badge bg-danger ms-1">{{ $bookings->where('status', 'cancelled')->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <h5 class="mb-3">Pencarian</h5>
                <div class="search-section">
                    <div class="search-field">
                        <input type="text" class="form-control" id="searchBooking" placeholder="Cari nama, email, kode booking...">
                    </div>
                    <div>
                        <input type="date" class="form-control" id="filterDate" title="Filter berdasarkan tanggal">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Booking Table -->
    <div class="booking-table-container">
        @if($bookings->count() > 0)
            <div class="table-responsive">
                <table class="table enhanced-table">
                    <thead>
                        <tr>
                            <th>Booking Info</th>
                            <th>Customer</th>
                            <th>Lapangan</th>
                            <th>Jadwal</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="bookingTableBody">
                        @foreach($bookings as $booking)
                        <tr class="booking-row" data-status="{{ $booking->status }}" data-date="{{ $booking->date }}" 
                            data-search="{{ strtolower($booking->user->name . ' ' . $booking->user->email . ' ' . $booking->id) }}">
                            <td>
                                <div class="booking-id">#{{ $booking->id }}</div>
                                <small class="text-muted">{{ $booking->created_at->format('d/m/Y H:i') }}</small>
                                @if($booking->notes)
                                    <br><small class="text-info"><i class="fas fa-comment"></i> Ada catatan</small>
                                @endif
                            </td>
                            <td>
                                <div class="customer-info">
                                    <div class="customer-avatar">
                                        {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $booking->user->name }}</div>
                                        <small class="text-muted">{{ $booking->user->email }}</small>
                                        @if($booking->contact_phone)
                                            <br><small class="text-muted">{{ $booking->contact_phone }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $booking->field->name }}</div>
                                <small class="text-muted">{{ $booking->field->category }}</small>
                            </td>
                            <td>
                                <div class="fw-bold">{{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}</div>
                                <small class="text-muted">{{ $booking->start_time }} - {{ $booking->end_time }}</small>
                                <br><small class="text-info">{{ $booking->duration }} jam</small>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $booking->status }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                                <small class="text-muted">{{ $booking->payment_status ?? 'unpaid' }}</small>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    @if($booking->status === 'pending')
                                        <button class="action-btn confirm" onclick="updateStatus({{ $booking->id }}, 'confirmed')">
                                            <i class="fas fa-check"></i>Konfirmasi
                                        </button>
                                        <button class="action-btn reject" onclick="updateStatus({{ $booking->id }}, 'cancelled')">
                                            <i class="fas fa-times"></i>Tolak
                                        </button>
                                    @endif
                                    <button class="action-btn view" onclick="viewBookingDetails({{ $booking->id }})">
                                        <i class="fas fa-eye"></i>Detail
                                    </button>
                                    @if($booking->contact_phone)
                                        <a href="https://wa.me/62{{ ltrim($booking->contact_phone, '0') }}" 
                                           class="action-btn contact" target="_blank">
                                            <i class="fab fa-whatsapp"></i>WhatsApp
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center p-3">
                {{ $bookings->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <h4>Belum Ada Booking</h4>
                <p>Booking akan muncul di sini ketika ada yang memesan lapangan Anda</p>
                <a href="{{ route('owner.fields') }}" class="btn btn-primary">
                    <i class="fas fa-futbol me-2"></i>Kelola Lapangan
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Update Status Booking
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div id="statusIcon" class="fs-1 mb-3"></div>
                    <p id="statusMessage" class="mb-0"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Batal
                </button>
                <form id="statusForm" method="POST" style="display: inline;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" id="newStatus">
                    <button type="submit" class="btn btn-primary" id="confirmButton">
                        <i class="fas fa-check me-2"></i>Konfirmasi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Booking Details Modal -->
<div class="modal fade booking-details-modal" id="bookingDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>Detail Booking
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="bookingDetailsContent">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterTabs = document.querySelectorAll('.filter-tab');
    const bookingRows = document.querySelectorAll('.booking-row');
    const searchInput = document.getElementById('searchBooking');
    const dateFilter = document.getElementById('filterDate');
    
    // Tab filtering
    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Update active tab
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            filterBookings();
        });
    });
    
    // Search functionality
    searchInput.addEventListener('input', filterBookings);
    dateFilter.addEventListener('change', filterBookings);
    
    function filterBookings() {
        const activeTab = document.querySelector('.filter-tab.active');
        const statusFilter = activeTab.dataset.status;
        const searchTerm = searchInput.value.toLowerCase();
        const dateValue = dateFilter.value;
        
        bookingRows.forEach(row => {
            const status = row.dataset.status;
            const searchData = row.dataset.search;
            const bookingDate = row.dataset.date;
            
            let showRow = true;
            
            // Status filter
            if (statusFilter !== 'all' && status !== statusFilter) {
                showRow = false;
            }
            
            // Search filter
            if (searchTerm && !searchData.includes(searchTerm)) {
                showRow = false;
            }
            
            // Date filter
            if (dateValue && bookingDate !== dateValue) {
                showRow = false;
            }
            
            row.style.display = showRow ? '' : 'none';
        });
        
        // Show empty state if no rows visible
        const visibleRows = Array.from(bookingRows).filter(row => row.style.display !== 'none');
        const tableBody = document.getElementById('bookingTableBody');
        
        if (visibleRows.length === 0) {
            if (!document.getElementById('emptyStateRow')) {
                const emptyRow = document.createElement('tr');
                emptyRow.id = 'emptyStateRow';
                emptyRow.innerHTML = `
                    <td colspan="7" class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5>Tidak ada booking yang sesuai</h5>
                        <p class="text-muted">Coba ubah filter atau kata kunci pencarian</p>
                    </td>
                `;
                tableBody.appendChild(emptyRow);
            }
        } else {
            const emptyRow = document.getElementById('emptyStateRow');
            if (emptyRow) {
                emptyRow.remove();
            }
        }
    }
});

// Status update functionality
function updateStatus(bookingId, newStatus) {
    const form = document.getElementById('statusForm');
    const message = document.getElementById('statusMessage');
    const button = document.getElementById('confirmButton');
    const icon = document.getElementById('statusIcon');
    
    form.action = `/owner/bookings/${bookingId}/status`;
    document.getElementById('newStatus').value = newStatus;
    
    if (newStatus === 'confirmed') {
        message.textContent = 'Apakah Anda yakin ingin mengkonfirmasi booking ini? Customer akan menerima notifikasi konfirmasi.';
        button.innerHTML = '<i class="fas fa-check me-2"></i>Konfirmasi';
        button.className = 'btn btn-success';
        icon.innerHTML = '<i class="fas fa-check-circle text-success"></i>';
    } else if (newStatus === 'cancelled') {
        message.textContent = 'Apakah Anda yakin ingin menolak booking ini? Customer akan menerima notifikasi penolakan.';
        button.innerHTML = '<i class="fas fa-times me-2"></i>Tolak';
        button.className = 'btn btn-danger';
        icon.innerHTML = '<i class="fas fa-times-circle text-danger"></i>';
    }
    
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
}

// View booking details
function viewBookingDetails(bookingId) {
    // In a real app, this would fetch data via AJAX
    const content = `
        <div class="detail-section">
            <h6><i class="fas fa-info-circle me-2"></i>Informasi Booking</h6>
            <div class="detail-item">
                <span class="detail-label">Kode Booking:</span>
                <span class="detail-value">#${bookingId}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Status:</span>
                <span class="detail-value">Pending</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Tanggal Dibuat:</span>
                <span class="detail-value">${new Date().toLocaleDateString('id-ID')}</span>
            </div>
        </div>
        
        <div class="detail-section">
            <h6><i class="fas fa-user me-2"></i>Informasi Customer</h6>
            <div class="detail-item">
                <span class="detail-label">Nama:</span>
                <span class="detail-value">John Doe</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Email:</span>
                <span class="detail-value">john@example.com</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Telepon:</span>
                <span class="detail-value">081234567890</span>
            </div>
        </div>
        
        <div class="detail-section">
            <h6><i class="fas fa-calendar me-2"></i>Detail Pemesanan</h6>
            <div class="detail-item">
                <span class="detail-label">Lapangan:</span>
                <span class="detail-value">Lapangan Futsal A</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Tanggal:</span>
                <span class="detail-value">${new Date().toLocaleDateString('id-ID')}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Waktu:</span>
                <span class="detail-value">16:00 - 18:00</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Durasi:</span>
                <span class="detail-value">2 jam</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Total Harga:</span>
                <span class="detail-value">Rp 300.000</span>
            </div>
        </div>
    `;
    
    document.getElementById('bookingDetailsContent').innerHTML = content;
    const modal = new bootstrap.Modal(document.getElementById('bookingDetailsModal'));
    modal.show();
}

// Quick actions
function exportBookings() {
    alert('Fitur export akan segera tersedia!');
}

function viewReports() {
    alert('Fitur laporan akan segera tersedia!');
}
</script>
@endpush
@endsection
