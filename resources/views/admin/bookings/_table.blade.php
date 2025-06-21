<div class="table-responsive">
    <table class="table table-bordered" id="bookings-table" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Kode Booking</th>
                <th>Pengguna</th>
                <th>Lapangan</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Status</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
            <tr class="booking-row" 
                data-booking-code="{{ $booking->booking_code }}"
                data-status="{{ $booking->status }}"
                data-date="{{ \Carbon\Carbon::parse($booking->date)->format('Y-m-d') }}">
                <td>
                    <span class="booking-code font-weight-bold">{{ $booking->booking_code }}</span>
                </td>
                <td>{{ $booking->user->name }}</td>
                <td>{{ $booking->field->name }}</td>
                <td>{{ \Carbon\Carbon::parse($booking->date)->format('d/m/Y') }}</td>
                <td>{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                <td>
                    <span class="badge {{ $booking->status === 'confirmed' ? 'badge-success' : ($booking->status === 'cancelled' ? 'badge-danger' : 'badge-warning') }} badge-pill py-2 px-3">
                        {{ ucfirst($booking->status) }}
                    </span>
                </td>
                <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-info btn-sm" title="Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                        
                        @if($booking->status === 'pending')
                        <form action="{{ route('bookings.confirm', $booking->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm" title="Konfirmasi" onclick="return confirm('Konfirmasi booking ini?')">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        @endif
                        
                        @if($booking->status !== 'cancelled')
                        <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm" title="Batalkan" onclick="return confirm('Yakin ingin membatalkan booking ini?')">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5>Tidak ada data booking</h5>
                        <p class="text-muted">Belum ada booking yang tersedia sesuai dengan filter yang dipilih.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-center mt-4">
    {{ $bookings->links() }}
</div>
