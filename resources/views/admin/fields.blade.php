@extends('layouts.app')
@section('title', 'Kelola Lapangan')

@push('styles')
<link href="{{ asset('css/admin-common.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="admin-container fade-in">
    <div class="admin-sidebar">        <div class="admin-profile">
            <div class="admin-avatar">
                @if(auth()->user()->profile_image)
                    <img src="{{ asset(auth()->user()->profile_image) }}" alt="{{ auth()->user()->name }}" class="admin-avatar-img" onerror="this.onerror=null; this.src='{{ asset('assets/images/default-avatar.png') }}'; this.parentNode.innerHTML = '<span>{{ substr(auth()->user()->name, 0, 1) }}</span>';">
                @else
                    <span>{{ substr(auth()->user()->name, 0, 1) }}</span>
                @endif
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
            <a href="{{ route('admin.fields') }}" class="admin-nav-item active">
                <i class="fas fa-futbol"></i>
                <span>Kelola Lapangan</span>
            </a>
            <a href="{{ route('admin.bookings') }}" class="admin-nav-item">
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
                        <h1 class="h3 mb-0 text-gray-800">Kelola Lapangan</h1>
                        <a href="{{ route('admin.fields.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus mr-1"></i> Tambah Lapangan
                        </a>
                    </div>
                </div>
            </div>
              <!-- Status Cards -->
            <div class="row mb-4">
                @php
                    $totalFields = \App\Models\Field::count();
                    $activeFields = \App\Models\Field::where('is_available', 1)->count();
                    $inactiveFields = \App\Models\Field::where('is_available', 0)->count();
                    $categoryCount = \App\Models\Field::select('category')->distinct()->count();
                @endphp
                
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Lapangan</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalFields }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-futbol fa-2x text-gray-300"></i>
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
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Aktif</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeFields }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Tidak Aktif</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $inactiveFields }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Jenis Olahraga</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $categoryCount }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-list fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Filters -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Filter Lapangan</h6>
                </div>
                <div class="card-body">
                    <form id="field-filter-form" class="row align-items-center" method="GET" action="{{ route('admin.fields') }}">
                        <div class="col-md-4 mb-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" id="search-field" name="search" class="form-control" placeholder="Cari lapangan..." value="{{ request('search') }}">
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <select id="category-filter" name="category" class="form-control">
                                <option value="">Semua Jenis</option>
                                <option value="futsal" {{ request('category') == 'futsal' ? 'selected' : '' }}>Futsal</option>
                                <option value="basket" {{ request('category') == 'basket' ? 'selected' : '' }}>Basket</option>
                                <option value="badminton" {{ request('category') == 'badminton' ? 'selected' : '' }}>Badminton</option>
                                <option value="voli" {{ request('category') == 'voli' ? 'selected' : '' }}>Voli</option>
                                <option value="tennis" {{ request('category') == 'tennis' ? 'selected' : '' }}>Tennis</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <select id="status-filter" name="status" class="form-control">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2 mb-3">
                            <div class="btn-group btn-block">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search mr-1"></i> Filter
                                </button>
                                <a href="{{ route('admin.fields') }}" id="reset-filter" class="btn btn-outline-secondary">
                                    <i class="fas fa-undo"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            
            <!-- Fields Table -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <ul class="nav nav-tabs card-header-tabs" id="field-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab">
                                Semua <span class="badge badge-primary">{{ $totalFields }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="active-tab" data-toggle="tab" href="#active" role="tab">
                                Aktif <span class="badge badge-success">{{ $activeFields }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="inactive-tab" data-toggle="tab" href="#inactive" role="tab">
                                Tidak Aktif <span class="badge badge-danger">{{ $inactiveFields }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="field-tabs-content">
                        <div class="tab-pane fade show active" id="all" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="fields-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th style="width: 100px;">Gambar</th>
                                            <th>Nama Lapangan</th>
                                            <th>Jenis</th>
                                            <th>Lokasi</th>
                                            <th>Harga</th>
                                            <th>Status</th>
                                            <th style="width: 150px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $fields = \App\Models\Field::latest()->paginate(10); @endphp
                                        @forelse($fields as $index => $field)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <img src="{{ $field->image_url ?? asset('assets/img/field-placeholder.jpg') }}" alt="{{ $field->name }}" class="img-thumbnail" width="80">
                                            </td>
                                            <td class="font-weight-bold">{{ $field->name }}</td>
                                            <td>{{ ucfirst($field->category ?? $field->sport_type) }}</td>
                                            <td>{{ $field->location }}</td>
                                            <td>Rp {{ number_format($field->price ?? $field->price_per_hour, 0, ',', '.') }}/jam</td>
                                            <td>
                                                <span class="status-badge {{ $field->is_available ? 'confirmed' : 'cancelled' }}">
                                                    {{ $field->is_available ? 'Aktif' : 'Tidak Aktif' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('fields.show', $field->id) }}" class="btn btn-sm btn-primary action-btn" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.fields.edit', $field->id) }}" class="btn btn-sm btn-info action-btn" title="Edit Lapangan">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.fields.destroy', $field->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger action-btn" title="Hapus" data-confirm="Yakin ingin menghapus lapangan ini?">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="fas fa-futbol fa-3x mb-3"></i>
                                                    <h4>Tidak ada lapangan tersedia</h4>
                                                    <p>Belum ada data lapangan yang tersedia untuk ditampilkan</p>
                                                    <a href="{{ route('admin.fields.create') }}" class="btn btn-primary">
                                                        <i class="fas fa-plus mr-2"></i> Tambah Lapangan Baru
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-3">
                                {{ $fields->links() }}
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="active" role="tabpanel">
                            <!-- Content will be loaded dynamically -->
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="inactive" role="tabpanel">
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

@push('scripts')
<script src="{{ asset('js/admin-common.js') }}"></script>
<script src="{{ asset('js/admin-fields.js') }}"></script>
@endpush
    
    .admin-table-container {
        background-color: var(--white);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    
    .fields-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .field-image {
        width: 80px;
        height: 60px;
        border-radius: var(--radius-sm);
        overflow: hidden;
    }
    
    .field-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .table-actions {
        display: flex;
        gap: 10px;
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
    
    .delete-button {
        background-color: var(--secondary-color);
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
    }
</style>
@endsection
