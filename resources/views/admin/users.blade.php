@extends('layouts.app')
@section('title', 'Kelola Pengguna')
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
        <div class="admin-header">
            <h1>Kelola Pengguna</h1>
            <div class="admin-actions">
                <button class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">
                    <i class="fas fa-user-plus"></i> Tambah Pengguna
                </button>
            </div>
        </div>
        
        <div class="admin-filters">
            <div class="filter-group">
                <input type="text" class="filter-input" placeholder="Cari pengguna...">
                <button class="filter-button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            
            <div class="filter-dropdowns">
                <select class="filter-select">
                    <option value="">Semua Peran</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
                
                <select class="filter-select">
                    <option value="">Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Tidak Aktif</option>
                </select>
            </div>
        </div>
        
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        
        <div class="admin-table-container">
            <table class="admin-table users-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. Telepon</th>
                        <th>Peran</th>
                        <th>Terdaftar</th>
                        <th>Status</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(\App\Models\User::latest()->paginate(10) as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar-small">
                                    <span>{{ substr($user->name, 0, 1) }}</span>
                                </div>
                                <span>{{ $user->name }}</span>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?? '-' }}</td>
                        <td>
                            <span class="role-badge {{ $user->is_admin ? 'admin' : 'user' }}">
                                {{ $user->is_admin ? 'Admin' : 'User' }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="status-toggle">
                                <input type="checkbox" id="status-{{ $user->id }}" class="toggle-input" {{ $user->is_active ? 'checked' : '' }}>
                                <label for="status-{{ $user->id }}" class="toggle-label"></label>
                            </div>
                        </td>
                        <td>
                            <div class="table-actions">
                                <button class="btn-icon" title="Edit" data-toggle="modal" data-target="#editUserModal-{{ $user->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                @if(!$user->is_admin)
                                <form action="#" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon delete-button" title="Hapus" onclick="return confirm('Yakin ingin menghapus pengguna ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="pagination-container">
                {{ \App\Models\User::latest()->paginate(10)->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pengguna Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#" method="POST" id="addUserForm">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Nomor Telepon</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="role">Peran</label>
                        <select class="form-control" id="role" name="role">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" checked>
                            <label class="custom-control-label" for="is_active">Aktif</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('addUserForm').submit()">Simpan</button>
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
    
    .admin-table-container {
        background-color: var(--white);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    
    .users-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .user-info {
        display: flex;
        align-items: center;
    }
    
    .user-avatar-small {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background-color: var(--primary-color);
        color: var(--white);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 600;
        margin-right: 10px;
    }
    
    .role-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .role-badge.admin {
        background-color: #e3f2fd;
        color: #1565c0;
    }
    
    .role-badge.user {
        background-color: #f5f5f5;
        color: #616161;
    }
    
    .status-toggle {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }
    
    .toggle-input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .toggle-label {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        border-radius: 34px;
        cursor: pointer;
        transition: var(--transition);
    }
    
    .toggle-label:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: var(--white);
        border-radius: 50%;
        transition: var(--transition);
    }
    
    .toggle-input:checked + .toggle-label {
        background-color: #4caf50;
    }
    
    .toggle-input:checked + .toggle-label:before {
        transform: translateX(26px);
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
    
    /* Modal Styles */
    .modal-content {
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-lg);
        border: none;
    }
    
    .modal-header {
        padding: 20px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .modal-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--primary-color);
    }
    
    .modal-body {
        padding: 20px;
    }
    
    .modal-footer {
        padding: 15px 20px;
        border-top: 1px solid var(--border-color);
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        transition: var(--transition);
    }
    
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(4, 62, 3, 0.1);
    }
    
    .custom-control {
        padding-left: 1.5rem;
        position: relative;
    }
    
    .custom-control-input {
        position: absolute;
        z-index: -1;
        opacity: 0;
    }
    
    .custom-control-label {
        position: relative;
        margin-bottom: 0;
        vertical-align: top;
        cursor: pointer;
    }
    
    .custom-control-label::before {
        position: absolute;
        top: 0;
        left: -1.5rem;
        display: block;
        width: 1rem;
        height: 1rem;
        content: "";
        background-color: #fff;
        border: 1px solid var(--border-color);
        border-radius: 0.25rem;
    }
    
    .custom-control-input:checked ~ .custom-control-label::before {
        color: #fff;
        border-color: var(--primary-color);
        background-color: var(--primary-color);
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
