@extends('layouts.app')
@section('title', 'Kelola Pengguna')

@push('styles')
<link href="{{ asset('css/admin-common.css') }}" rel="stylesheet">
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
            <a href="{{ route('admin.bookings') }}" class="admin-nav-item">
                <i class="fas fa-calendar-check"></i>
                <span>Kelola Booking</span>
            </a>
            <a href="{{ route('admin.users') }}" class="admin-nav-item active">
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
            <div class="row mb-4">                <div class="col">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="h3 mb-0 text-gray-800">Kelola Pengguna</h1>
                    </div>
                </div>
            </div>
            
            <!-- Status Cards -->
            <div class="row mb-4">
                @php
                    $totalUsers = $users->total();
                    $adminCount = \App\Models\User::where('role', 'admin')->count();
                    $regularUserCount = \App\Models\User::where('role', 'user')->count();
                    $fieldOwnerCount = \App\Models\User::where('role', 'field_owner')->count();
                @endphp
                
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pengguna</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
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
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Regular User</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $regularUserCount }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user fa-2x text-gray-300"></i>
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
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Field Owner</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $fieldOwnerCount }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-building fa-2x text-gray-300"></i>
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
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Admin</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $adminCount }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Filters -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Filter Pengguna</h6>
                </div>
                <div class="card-body">
                    <form id="user-filter-form" class="row align-items-center" method="GET" action="{{ route('admin.users') }}">
                        <div class="col-md-4 mb-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" id="search-user" name="search" class="form-control" 
                                       placeholder="Cari nama atau email..." value="{{ request('search') }}">
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <select id="role-filter" name="role" class="form-control">
                                <option value="">Semua Role</option>
                                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Regular User</option>
                                <option value="field_owner" {{ request('role') == 'field_owner' ? 'selected' : '' }}>Field Owner</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
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
                                <a href="{{ route('admin.users') }}" id="reset-filter" class="btn btn-outline-secondary">
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
            
            <!-- Users Table -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <ul class="nav nav-tabs card-header-tabs" id="user-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab">
                                Semua <span class="badge badge-primary">{{ $totalUsers }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="user-tab" data-toggle="tab" href="#user-role" role="tab">
                                User <span class="badge badge-success">{{ $regularUserCount }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="owner-tab" data-toggle="tab" href="#owner-role" role="tab">
                                Field Owner <span class="badge badge-warning">{{ $fieldOwnerCount }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="admin-tab" data-toggle="tab" href="#admin-role" role="tab">
                                Admin <span class="badge badge-danger">{{ $adminCount }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="user-tabs-content">
                        <div class="tab-pane fade show active" id="all" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="users-table">
                                    <thead>
                                        <tr>
                                            <th>Pengguna</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Terdaftar</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="admin-avatar bg-primary text-white mr-3" style="width: 40px; height: 40px;">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="font-weight-bold">{{ $user->name }}</div>
                                                        <small class="text-muted">{{ $user->email }}</small>
                                                        @if($user->phone)
                                                        <br><small class="text-muted">{{ $user->phone }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($user->role == 'admin')
                                                    <span class="status-badge cancelled">Admin</span>
                                                @elseif($user->role == 'field_owner')
                                                    <span class="status-badge pending">Field Owner</span>
                                                @else
                                                    <span class="status-badge confirmed">User</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->email_verified_at)
                                                    <span class="status-badge confirmed">Aktif</span>
                                                @else
                                                    <span class="status-badge cancelled">Tidak Aktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div>{{ $user->created_at->format('d M Y') }}</div>
                                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-primary action-btn dropdown-toggle" type="button" 
                                                                data-toggle="dropdown" aria-expanded="false" title="Ubah Role">
                                                            <i class="fas fa-user-tag"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <form method="POST" action="{{ route('admin.users.role', $user) }}" class="d-inline">
                                                                @csrf @method('PUT')
                                                                <input type="hidden" name="role" value="user">
                                                                <button type="submit" class="dropdown-item" {{ $user->role == 'user' ? 'disabled' : '' }}>
                                                                    <i class="fas fa-user mr-1"></i>User
                                                                </button>
                                                            </form>
                                                            <form method="POST" action="{{ route('admin.users.role', $user) }}" class="d-inline">
                                                                @csrf @method('PUT')
                                                                <input type="hidden" name="role" value="field_owner">
                                                                <button type="submit" class="dropdown-item" {{ $user->role == 'field_owner' ? 'disabled' : '' }}>
                                                                    <i class="fas fa-building mr-1"></i>Field Owner
                                                                </button>
                                                            </form>
                                                            @if(auth()->user()->id != $user->id)
                                                            <form method="POST" action="{{ route('admin.users.role', $user) }}" class="d-inline">
                                                                @csrf @method('PUT')
                                                                <input type="hidden" name="role" value="admin">
                                                                <button type="submit" class="dropdown-item" {{ $user->role == 'admin' ? 'disabled' : '' }}>
                                                                    <i class="fas fa-shield-alt mr-1"></i>Admin
                                                                </button>
                                                            </form>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    @if(auth()->user()->id != $user->id)
                                                    <form method="POST" action="{{ route('admin.users.toggle', $user) }}" class="d-inline">
                                                        @csrf @method('PUT')
                                                        <button type="submit" class="btn btn-sm {{ $user->email_verified_at ? 'btn-danger' : 'btn-success' }} action-btn"
                                                                data-confirm="Yakin ingin {{ $user->email_verified_at ? 'nonaktifkan' : 'aktifkan' }} pengguna ini?" 
                                                                title="{{ $user->email_verified_at ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                            <i class="fas fa-power-off"></i>
                                                        </button>
                                                    </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <div class="empty-state">
                                                    <i class="fas fa-users fa-3x mb-3"></i>
                                                    <h4>Tidak ada pengguna</h4>
                                                    <p>Belum ada pengguna yang sesuai dengan filter</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-3">
                                {{ $users->appends(request()->query())->links() }}
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="user-role" role="tabpanel">
                            <!-- Content will be loaded dynamically -->
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="owner-role" role="tabpanel">
                            <!-- Content will be loaded dynamically -->
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="admin-role" role="tabpanel">
                            <!-- Content will be loaded dynamically -->
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            <!-- End of Users Content -->
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/admin-common.js') }}"></script>
<script src="{{ asset('js/admin-users.js') }}"></script>
@endpush
@endsection
