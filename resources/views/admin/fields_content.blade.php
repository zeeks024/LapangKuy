<div class="admin
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
        <div class="admin-header">
            <h1>Kelola Lapangan</h1>
            <div class="admin-actions">
                <a href="{{ route('admin.fields.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Lapangan
                </a>
            </div>
        </div>
        
        <div class="admin-filters">
            <div class="filter-group">
                <input type="text" class="filter-input" placeholder="Cari lapangan...">
                <button class="filter-button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            
            <div class="filter-dropdowns">
                <select class="filter-select">
                    <option value="">Jenis Olahraga</option>
                    <option value="futsal">Futsal</option>
                    <option value="basket">Basket</option>
                    <option value="badminton">Badminton</option>
                    <option value="voli">Voli</option>
                    <option value="tennis">Tennis</option>
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
            <table class="admin-table fields-table">
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
                    @foreach(\App\Models\Field::latest()->paginate(10) as $index => $field)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="field-image">
                                <img src="{{ $field->image_url ?? 'https://placehold.co/80x60/043E03/FFFFFF?text=IMG' }}" alt="{{ $field->name }}">
                            </div>
                        </td>
                        <td>{{ $field->name }}</td>
                        <td>{{ ucfirst($field->category) }}</td>
                        <td>{{ $field->location }}</td>
                        <td>Rp {{ number_format($field->price, 0, ',', '.') }}/jam</td>
                        <td>
                            <span class="status-badge confirmed">
                                Aktif
                            </span>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('fields.show', $field->id) }}" class="btn-icon" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.fields.edit', $field->id) }}" class="btn-icon" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.fields.destroy', $field->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon delete-button" title="Hapus" onclick="return confirm('Yakin ingin menghapus lapangan ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="pagination-container">
                {{ \App\Models\Field::latest()->paginate(10)->links() }}
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
        border: none;
        text-decoration: none;
    }
    
    .btn-icon:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        color: var(--white);
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
    
    .status-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
    }
    
    .status-badge.confirmed {
        background-color: #e6f4ea;
        color: #0d652d;
    }
    
    .status-badge.cancelled {
        background-color: #fdeaea;
        color: #d93025;
    }
    
    /* CSS Variables */
    :root {
        --white: #ffffff;
        --primary-color: #007bff;
        --secondary-color: #dc3545;
        --radius-sm: 6px;
        --radius-md: 8px;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
        --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
        --transition: all 0.3s ease;
        --border-color: #dee2e6;
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
