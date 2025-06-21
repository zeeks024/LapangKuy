<div class="table-responsive">
    <table class="table table-hover mb-0">
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

@if($fields->hasPages())
<div class="mt-3">
    {{ $fields->links() }}
</div>
@endif
