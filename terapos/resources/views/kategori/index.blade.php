@extends('app.master')

@section('content')
    <div class="container-fluid">
        <div class="mb-4">
            <h2 class="fw-bold">Kategori Produk</h2>
            <p class="text-muted">Kelola kategori produk TERAPOS.</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Tambah Kategori</h5>
                <form action="{{ route('kategori.store') }}" method="POST" class="row g-2">
                    @csrf
                    <div class="col-md-9">
                        <label for="nama_kategori" class="form-label">Nama Kategori</label>
                        <input id="nama_kategori" type="text" name="nama_kategori" class="form-control"
                            value="{{ old('nama_kategori') }}" maxlength="100" required>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Tambah Kategori</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Jumlah Produk</th>
                            <th style="min-width: 320px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td class="fw-semibold">{{ $category->nama_kategori }}</td>
                                <td>{{ $category->products_count }}</td>
                                <td>
                                    <form action="{{ route('kategori.update', $category) }}" method="POST"
                                        class="d-flex gap-2 mb-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="nama_kategori" class="form-control"
                                            value="{{ $category->nama_kategori }}" maxlength="100" required
                                            aria-label="Nama kategori">
                                        <button type="submit" class="btn btn-outline-primary">Simpan</button>
                                    </form>
                                    <form action="{{ route('kategori.destroy', $category) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('Hapus kategori ini? Produk yang menggunakannya akan menjadi tanpa kategori.')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">Belum ada kategori.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
