@extends('app.master')

@section('content')
    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="mb-4">

            <h2 class="fw-bold">
                Tambah Produk
            </h2>

            <p class="text-muted">Tambahkan produk baru ke TERAPOS.</p>

        </div>

        {{-- ERROR VALIDATION --}}
        @if ($errors->any())
            <div class="alert alert-danger">

                <ul class="mb-0">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>
        @endif

        {{-- FORM CARD --}}
        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <form action="{{ route('produk.store') }}" method="POST">

                    @csrf

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Kode Produk</label>
                            <input type="text" name="kode_produk" class="form-control"
                                value="{{ $generatedCode }}" required readonly>
                            <small class="text-muted">Dibuat otomatis oleh sistem.</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Barcode</label>
                            <input type="text" name="barcode" class="form-control"
                                value="{{ $generatedBarcode }}" required readonly>
                            <small class="text-muted">Dibuat otomatis oleh sistem.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control" value="{{ old('nama_produk') }}"
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Kategori</label>
                            <select name="category_id" class="form-select">
                                <option value="">Tanpa kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                        {{ $category->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Merk</label>
                            <input type="text" name="merk" class="form-control" value="{{ old('merk') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Satuan</label>
                            <select name="satuan" class="form-select" required>
                                @foreach (['pcs', 'pack', 'box', 'botol', 'kaleng', 'sachet', 'kg', 'gram', 'liter', 'ml', 'unit'] as $unit)
                                    <option value="{{ $unit }}" @selected(old('satuan', 'pcs') === $unit)>{{ $unit }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Harga Beli</label>
                            <input type="number" name="harga_beli" class="form-control" value="{{ old('harga_beli') }}"
                                min="0" step="0.01" required>
                            <small class="form-text text-muted">Harga modal produk.</small>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Harga Jual</label>
                            <input type="number" name="harga_jual" class="form-control" value="{{ old('harga_jual') }}"
                                min="0" step="0.01" required>
                            <small class="form-text text-muted">Harga jual harus lebih besar dari harga beli.</small>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Stok Minimum</label>
                            <input type="number" name="stok_minimum" class="form-control"
                                value="{{ old('stok_minimum', 1) }}" min="0" required>
                        </div>
                        <div class="col-12 mb-4">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea name="deskripsi" rows="4" class="form-control">{{ old('deskripsi') }}</textarea>
                        </div>

                    </div>

                    {{-- BUTTON --}}
                    <div class="d-flex gap-2">

                        <button type="submit" class="btn btn-primary">

                            Simpan Produk

                        </button>

                        <a href="{{ route('produk.index') }}" class="btn btn-outline-secondary">

                            Kembali

                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>
@endsection
