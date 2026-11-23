@extends('app.master')

@section('title', 'Manajemen User — TERAPOS')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Manajemen User</h1>
            <p class="text-muted mb-0">Kelola akses Owner dan Co-Owner.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('users.create') }}"><i class="bi bi-person-plus me-1"></i>Tambah User</a>
    </div>
    @if (session('success')) <div class="alert alert-success" role="status">{{ session('success') }}</div> @endif
    @if ($errors->any())
        <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td><td>{{ $user->email }}</td><td>{{ $user->display_role }}</td>
                            <td><span class="badge {{ $user->aktif ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $user->aktif ? 'Aktif' : 'Tidak Aktif' }}</span></td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('users.edit', $user) }}">Edit</a>
                                @if (!$user->is(auth()->user()))
                                    <form class="d-inline" method="POST" action="{{ route('users.status', $user) }}"
                                        onsubmit="return confirm('Yakin {{ $user->aktif ? 'menonaktifkan' : 'mengaktifkan' }} akun ini?')">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm {{ $user->aktif ? 'btn-outline-danger' : 'btn-outline-success' }}" type="submit">
                                            {{ $user->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td class="text-center text-muted py-4" colspan="5">Belum ada akun.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">{{ $users->links() }}</div>
    </div>
@endsection
