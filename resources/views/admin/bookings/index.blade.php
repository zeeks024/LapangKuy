@extends('layouts.app')
@section('title', 'Kelola Booking')

@push('styles')
<link href="{{ asset('css/admin-bookings.css') }}" rel="stylesheet">
@endpush
@section('content')
<div class="admin-container fade-in">
    <div class="admin-sidebar">
        <div class="admin-profile">
            <div class="admin-avatar">
                <span>{{ substr(auth()->user()->name, 0, 1) }}</span>
            </div>
            <div class="admin-info">
                <h3>{{ auth()->user()->name }}</h3>
                <p>Administrator</p>
            </div>
        </div>
        
        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-item">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.fields') }}" class="admin-nav-item">
                <i class="fas fa-futbol"></i>
                <span>Kelola Lapangan</span>
            </a>
            <a href="{{ route('admin.bookings') }}" class="admin-nav-item active">
                <i class="fas fa-calendar-check"></i>
                <span>Kelola Booking</span>
            </a>
            <a href="{{ route('admin.users') }}" class="admin-nav-item">
                <i class="fas fa-users"></i>
                <span>Kelola Pengguna</span>
            </a>
            <a href="{{ route('home') }}" class="admin-nav-item">
                <i class="fas fa-home"></i>
                <span>Kembali ke Website</span>
            </a>
        </nav>
    </div>
    
    <div class="admin-content">
        <div class="container-fluid p-0">
            <div class="row mb-4">
                <div class="col">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="h3 mb-0 text-gray-800">Kelola Booking</h1>
                        <a href="{{ route('admin.fields') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-map-marker-alt mr-1"></i> Kelola Lapangan
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Status Cards -->
            <div class="row mb-4">                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Booking</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCount }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Confirmed</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $confirmedCount }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingCount }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clock fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Cancelled</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $cancelledCount }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Filters and Alert -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Filter Booking</h6>
                </div>                <div class="card-body">
                    <form id="booking-filter-form" class="row align-items-center" method="GET" action="{{ route('admin.bookings') }}">
                        <div class="col-md-4 mb-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" id="search-booking" name="search" class="form-control" placeholder="Cari kode booking..." value="{{ request('search') }}">
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <select id="status-filter" name="status" class="form-control">
                                <option value="">Semua Status</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <input type="date" id="date-filter" name="date" class="form-control" value="{{ request('date') }}">
                        </div>
                        
                        <div class="col-md-2 mb-3">
                            <div class="btn-group btn-block">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search mr-1"></i> Filter
                                </button>
                                <a href="{{ route('admin.bookings') }}" id="reset-filter" class="btn btn-outline-secondary">
                                    <i class="fas fa-undo"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            
            <!-- Booking Table -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <ul class="nav nav-tabs card-header-tabs" id="booking-tabs" role="tablist">                        <li class="nav-item">
                            <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab">
                                Semua <span class="badge badge-primary">{{ $totalCount }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="confirmed-tab" data-toggle="tab" href="#confirmed" role="tab">
                                Confirmed <span class="badge badge-success">{{ $confirmedCount }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pending-tab" data-toggle="tab" href="#pending" role="tab">
                                Pending <span class="badge badge-warning">{{ $pendingCount }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="cancelled-tab" data-toggle="tab" href="#cancelled" role="tab">
                                Cancelled <span class="badge badge-danger">{{ $cancelledCount }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="booking-tabs-content">                        <div class="tab-pane fade show active" id="all" role="tabpanel">
                            @include('admin.bookings._table')
                        </div>
                        
                        <div class="tab-pane fade" id="confirmed" role="tabpanel">
                            <!-- Content will be loaded dynamically -->
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="pending" role="tabpanel">
                            <!-- Content will be loaded dynamically -->
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="cancelled" role="tabpanel">
                            <!-- Content will be loaded dynamically -->
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Admin structure styles are already added in the dashboard */
    
    .admin-filters {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    
    .filter-group {
        display: flex;
        align-items: center;
        background-color: var(--white);
        border-radius: var(--radius-sm);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }
    
    .filter-input {
        border: none;
        padding: 12px 15px;
        width: 300px;
    }
    
    .filter-button {
        background-color: transparent;
        border: none;
        padding: 12px 15px;
        color: var(--primary-color);
        cursor: pointer;
    }
    
    .filter-dropdowns {
        display: flex;
        gap: 15px;
    }
    
    .filter-select {
        padding: 12px 15px;
        border: none;
        border-radius: var(--radius-sm);
        background-color: var(--white);
        box-shadow: var(--shadow-sm);
        min-width: 160px;
    }
    
    .booking-tabs {
        display: flex;
        margin-bottom: 20px;
        background-color: var(--white);
        border-radius: var(--radius-sm);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    
    .booking-tab {
        padding: 15px 20px;
        flex: 1;
        text-align: center;
        color: var(--light-text);
        border-bottom: 3px solid transparent;
        transition: var(--transition);
    }
    
    .booking-tab:hover {
        color: var(--primary-color);
    }
    
    .booking-tab.active {
        color: var(--primary-color);
        border-bottom-color: var(--primary-color);
        font-weight: 500;
    }
    
    .admin-table-container {
        background-color: var(--white);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    
    .bookings-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .booking-code {
        font-family: 'Roboto Mono', monospace;
        font-size: 13px;
        background-color: #f5f5f5;
        padding: 3px 6px;
        border-radius: 4px;
    }
    
    .table-actions {
        display: flex;
        gap: 8px;
    }
    
    .btn-icon {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white);
        background-color: var(--primary-color);
        transition: var(--transition);
    }
    
    .btn-icon:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    .btn-icon i {
        font-size: 14px;
    }
    
    .confirm-button {
        background-color: #4caf50;
    }
    
    .delete-button {
        background-color: var(--secondary-color);
    }
    
    .status-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .status-badge.confirmed {
        background-color: #e6f4ea;
        color: #0d652d;
    }
    
    .status-badge.cancelled {
        background-color: #fbe9e7;
        color: #c62828;
    }
    
    .status-badge.pending {
        background-color: #fff8e1;
        color: #ff8f00;
    }
    
    .pagination-container {
        padding: 20px;
        display: flex;
        justify-content: center;
    }
    
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: var(--radius-sm);
    }
    
    .alert-success {
        background-color: #e6f4ea;
        color: #0d652d;
        border: 1px solid rgba(13, 101, 45, 0.2);
    }
    
    @media (max-width: 992px) {
        .admin-filters {
            flex-direction: column;
            gap: 15px;
        }
        
        .filter-group {
            width: 100%;
        }
        
        .filter-input {
            flex: 1;
        }
        
        .filter-dropdowns {
            width: 100%;
        }
        
        .filter-select {
            flex: 1;
        }
        
        .booking-tabs {
            flex-wrap: wrap;
        }
        
        .booking-tab {
            padding: 10px;
            font-size: 14px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.booking-tab');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('active'));
            
            // Add active class to clicked tab
            this.classList.add('active');
            
            // In a real app, you would filter the table data here
            // For demo purposes, we'll just show a message
            console.log('Tab clicked:', this.textContent.trim());
        });
    });
});
</script>
@push('scripts')
<script src="{{ asset('js/admin-bookings.js') }}"></script>
<script src="{{ asset('js/admin-bookings-tabs.js') }}"></script>
@endpush
@endsection
