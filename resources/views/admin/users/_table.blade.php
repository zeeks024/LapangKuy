<div class="table-responsive">
    <table class="table table-hover mb-0">
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

@if($users->hasPages())
<div class="mt-3">
    {{ $users->appends(request()->query())->links() }}
</div>
@endif
