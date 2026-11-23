@extends('app.master')

@section('title', ($managedUser->exists ? 'Edit User' : 'Tambah User') . ' — TERAPOS')

@section('content')
    <div class="d-flex justify-content-between align-items-center gap-3 mb-4">
        <div><h1 class="h3 fw-bold mb-1">{{ $managedUser->exists ? 'Edit User' : 'Tambah User' }}</h1></div>
        <a class="btn btn-outline-secondary" href="{{ route('users.index') }}">Batal</a>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <form method="POST" action="{{ $managedUser->exists ? route('users.update', $managedUser) : route('users.store') }}" class="card border-0 shadow-sm">
        @csrf
        @if ($managedUser->exists) @method('PUT') @endif
        <div class="card-body row g-3">
            <div class="col-md-6">
                <label for="name" class="form-label">Nama</label>
                <input id="name" name="name" class="form-control" value="{{ old('name', $managedUser->name) }}" required>
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">Email / Username</label>
                <input id="email" type="email" name="email" class="form-control" value="{{ old('email', $managedUser->email) }}" required>
            </div>
            <div class="col-md-6">
                <label for="password" class="form-label">Password {{ $managedUser->exists ? '(kosongkan jika tidak diubah)' : '' }}</label>
                <input id="password" type="password" name="password" class="form-control" {{ $managedUser->exists ? '' : 'required' }}>
            </div>
            <div class="col-md-6">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" {{ $managedUser->exists ? '' : 'required' }}>
            </div>
            <div class="col-md-6">
                <label for="role" class="form-label">Role</label>
                <select id="role" name="role" class="form-select" required>
                    <option value="owner" @selected(old('role', $managedUser->role) === 'owner')>Owner</option>
                    <option value="co_owner" @selected(old('role', $managedUser->role) === 'co_owner')>Co-Owner</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="aktif" class="form-label">Status</label>
                <select id="aktif" name="aktif" class="form-select" required>
                    <option value="1" @selected((string) old('aktif', $managedUser->exists ? (int) $managedUser->aktif : 1) === '1')>Aktif</option>
                    <option value="0" @selected((string) old('aktif', $managedUser->exists ? (int) $managedUser->aktif : 1) === '0')>Tidak Aktif</option>
                </select>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-end">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan User</button>
        </div>
    </form>
@endsection
