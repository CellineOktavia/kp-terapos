@extends('app.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1">Kategori</h2>
                <p class="text-muted mb-0">Kelola kategori barang TERAPOS.</p>
            </div>
            <a href="#form-kategori" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>
                Tambah Kategori
            </a>
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

        <div class="card shadow-sm mb-4" id="form-kategori">
            <div class="card-body">
                <h5 class="card-title">Kategori Baru</h5>
                <form action="{{ route('kategori.store') }}" method="POST" class="row g-2">
                    @csrf
                    <div class="col-md-10">
                        <label for="nama_kategori" class="form-label">Nama Kategori</label>
                        <input id="nama_kategori" type="text" name="nama_kategori" class="form-control"
                            value="{{ old('nama_kategori') }}" maxlength="100" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Tambah</button>
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
                            <th>Jumlah Barang</th>
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
                                    <button type="button" class="btn btn-outline-danger btn-sm"
                                        data-bs-toggle="modal" data-bs-target="#deleteCategoryModal"
                                        data-delete-url="{{ route('kategori.destroy', $category) }}"
                                        data-category-name="{{ $category->nama_kategori }}">
                                            Hapus
                                    </button>
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

    <div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Hapus Kategori?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Kategori <strong id="delete-category-name"></strong> akan dihapus. Barang terkait
                        akan menjadi tanpa kategori.</p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                    <form id="delete-category-form" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Ya, Hapus Kategori</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('deleteCategoryModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            document.getElementById('delete-category-form').action = button.dataset.deleteUrl;
            document.getElementById('delete-category-name').textContent = button.dataset.categoryName;
        });
    </script>
@endpush
