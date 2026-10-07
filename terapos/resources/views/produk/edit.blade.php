@extends('app.master')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
@endpush

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="fw-bold">
                    Edit Produk
                </h2>

                <p class="text-muted">Perbarui informasi produk TERAPOS.</p>

            </div>

        </div>

        @if ($errors->any())
            <div class="alert alert-danger">

                <ul class="mb-0">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>
        @endif

        <div class="card shadow-sm">

            <div class="card-body">

                <form action="{{ route('produk.update', $product->id) }}" method="POST">

                    @csrf
                    @method('PUT')

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kode Produk</label>
                            <input type="text" name="kode_produk" class="form-control"
                                value="{{ $product->kode_produk }}" required readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Barcode</label>
                            <input type="text" name="barcode" class="form-control"
                                value="{{ $product->barcode }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control"
                                value="{{ old('nama_produk', $product->nama_produk) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="category_id" class="form-select">
                                <option value="">Tanpa kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                                        {{ $category->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Supplier</label>
                            <select name="supplier_id" class="form-select select2">
                                <option value="">Tanpa supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" @selected(old('supplier_id', $product->supplier_id) == $supplier->id)>
                                        {{ $supplier->nama_supplier }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Merk</label>
                            <input type="text" name="merk" class="form-control"
                                value="{{ old('merk', $product->merk) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Satuan</label>
                            <select name="satuan" class="form-select" required>
                                @php($units = ['pcs', 'pack', 'box', 'botol', 'kaleng', 'sachet', 'kg', 'gram', 'liter', 'ml', 'unit'])
                                @if ($product->satuan && !in_array($product->satuan, $units, true))
                                    <option value="{{ $product->satuan }}" @selected(old('satuan', $product->satuan) === $product->satuan)>
                                        {{ $product->satuan }} (satuan saat ini)
                                    </option>
                                @endif
                                @foreach ($units as $unit)
                                    <option value="{{ $unit }}" @selected(old('satuan', $product->satuan ?: 'pcs') === $unit)>
                                        {{ $unit }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Stok Saat Ini</label>
                            <input type="text" class="form-control" value="{{ $product->stok }} {{ $product->satuan }}"
                                readonly>
                            <small class="text-muted">
                                Perubahan stok dilakukan melalui Pembelian, Penjualan, atau Penyesuaian Stok.
                            </small>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Stok Minimum</label>
                            <input type="number" name="stok_minimum" class="form-control"
                                value="{{ old('stok_minimum', $product->stok_minimum) }}" min="0" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Harga Beli</label>
                            <input type="number" name="harga_beli" class="form-control"
                                value="{{ old('harga_beli', $product->harga_beli) }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Harga Jual</label>
                            <input type="number" name="harga_jual" class="form-control"
                                value="{{ old('harga_jual', $product->harga_jual) }}" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" rows="4" class="form-control">{{ old('deskripsi', $product->deskripsi) }}</textarea>
                        </div>

                    </div>

                    <button type="submit" class="btn btn-primary">

                        Simpan Perubahan

                    </button>

                    <a href="{{ route('produk.index') }}" class="btn btn-secondary">

                        Kembali

                    </a>

                </form>

            </div>

        </div>

    </div>

@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                placeholder: 'Cari data...',
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endpush
