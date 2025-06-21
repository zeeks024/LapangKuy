@extends('layouts.app')

@section('title', 'Kelola Lapangan')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-0">Kelola Lapangan</h1>
                <a href="{{ route('owner.fields.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Lapangan
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($fields->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Gambar</th>
                                <th>Nama Lapangan</th>
                                <th>Kategori</th>
                                <th>Lokasi</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fields as $field)
                            <tr>
                                <td>
                                    @if($field->image_url)
                                        <img src="{{ $field->image_url }}" alt="{{ $field->name }}" 
                                             class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                             style="width: 60px; height: 60px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $field->name }}</strong>
                                    @if($field->promo)
                                        <span class="badge bg-success ms-1">PROMO</span>
                                    @endif
                                </td>
                                <td>{{ $field->category }}</td>
                                <td>{{ $field->location }}</td>
                                <td>Rp {{ number_format($field->price, 0, ',', '.') }}/jam</td>
                                <td>
                                    <span class="badge bg-success">Aktif</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('fields.show', $field) }}" 
                                           class="btn btn-outline-primary" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('owner.fields.edit', $field) }}" 
                                           class="btn btn-outline-secondary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" 
                                                onclick="confirmDelete({{ $field->id }})" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $fields->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-futbol fa-3x text-muted mb-3"></i>
                    <h5>Belum Ada Lapangan</h5>
                    <p class="text-muted">Mulai dengan menambahkan lapangan pertama Anda</p>
                    <a href="{{ route('owner.fields.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Lapangan
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus lapangan ini? Tindakan ini tidak dapat dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(fieldId) {
    const form = document.getElementById('deleteForm');
    form.action = `/owner/fields/${fieldId}`;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endsection
