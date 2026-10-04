@extends('app.master')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />

    <style>
        .card {
            border-radius: 18px;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
        }

        .select2-container--bootstrap-5 .select2-selection {
            border-radius: 12px !important;
            min-height: 42px;
        }

        .input-group-text {
            background: #f8fafc;
            font-weight: 600;
        }

        .section-title {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 1rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="mb-4">

            <h2 class="fw-bold">
                Edit Faktur Pembelian
            </h2>

            <p class="text-muted">
                Perbarui data transaksi pembelian supplier pada sistem SPARTA
            </p>

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

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <form action="{{ route('faktur.update', $faktur) }}" method="POST">

                    @csrf
                    @method('PUT')

                    {{-- INFORMASI FAKTUR --}}
                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-semibold">
                                Nomor Faktur
                            </label>

                            <input type="text" class="form-control bg-light" value="{{ $faktur->nomor_faktur }}"
                                readonly>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-semibold">
                                Tanggal
                            </label>

                            <input type="date" name="tanggal" class="form-control"
                                value="{{ \Carbon\Carbon::parse($faktur->tanggal)->format('Y-m-d') }}" required>

                        </div>

                    </div>

                    {{-- SUPPLIER --}}
                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            Supplier
                        </label>

                        <select name="supplier_id" id="supplier_id" class="form-select" required>

                            <option value="">
                                Pilih Supplier
                            </option>

                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ $supplier->id == $faktur->supplier_id ? 'selected' : '' }}>

                                    {{ $supplier->nama_supplier }}

                                </option>
                            @endforeach

                        </select>

                    </div>

                    <hr>

                    <h5 class="section-title">
                        Detail Produk
                    </h5>

                    <input type="hidden" name="product_id" id="product_id" value="{{ $detail->product_id ?? '' }}">

                    <div class="row">

                        {{-- KODE PRODUK --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label fw-semibold">
                                Kode Produk
                            </label>

                            <select id="kode_produk" class="form-select product-select">

                                <option value="">
                                    Pilih Kode Produk
                                </option>

                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-harga="{{ $product->harga_beli }}"
                                        data-stok="{{ $product->stok }}"
                                        {{ ($detail->product_id ?? '') == $product->id ? 'selected' : '' }}>

                                        {{ $product->kode_produk }}

                                    </option>
                                @endforeach

                            </select>

                        </div>

                        {{-- NAMA PRODUK --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label fw-semibold">
                                Nama Produk
                            </label>

                            <select id="nama_produk" class="form-select product-select">

                                <option value="">
                                    Pilih Nama Produk
                                </option>

                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-harga="{{ $product->harga_beli }}"
                                        data-stok="{{ $product->stok }}"
                                        {{ ($detail->product_id ?? '') == $product->id ? 'selected' : '' }}>

                                        {{ $product->nama_produk }}

                                    </option>
                                @endforeach

                            </select>

                        </div>

                        {{-- MERK --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label fw-semibold">
                                Merk
                            </label>

                            <select id="merk_produk" class="form-select product-select">

                                <option value="">
                                    Pilih Merk
                                </option>

                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-harga="{{ $product->harga_beli }}"
                                        data-stok="{{ $product->stok }}"
                                        {{ ($detail->product_id ?? '') == $product->id ? 'selected' : '' }}>

                                        {{ $product->merk }}
                                        -
                                        {{ $product->nama_produk }}

                                    </option>
                                @endforeach

                            </select>

                        </div>

                    </div>

                    {{-- DETAIL PEMBELIAN --}}
                    <div class="row">

                        <div class="col-md-4 mb-3">

                            <label class="form-label fw-semibold">
                                Quantity
                            </label>

                            <input type="number" name="qty" id="qty" class="form-control"
                                value="{{ $detail->qty ?? '' }}" min="1" required>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label class="form-label fw-semibold">
                                Harga Beli
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    Rp
                                </span>

                                <input type="number" name="harga" id="harga" class="form-control"
                                    value="{{ $detail->harga ?? '' }}" required>

                            </div>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label class="form-label fw-semibold">
                                Subtotal
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    Rp
                                </span>

                                <input type="text" id="subtotal" class="form-control bg-light fw-bold" readonly>

                            </div>

                        </div>

                    </div>

                    {{-- BUTTON --}}
                    <div class="d-flex gap-2 mt-4">

                        <button type="submit" class="btn btn-primary">

                            Simpan Perubahan

                        </button>

                        <a href="{{ route('faktur.index') }}" class="btn btn-outline-secondary">

                            <i class="bi bi-arrow-left me-2"></i>
                            Kembali

                        </a>

                    </div>

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

            let isSyncing = false;

            $('#supplier_id').select2({
                theme: 'bootstrap-5',
                placeholder: 'Cari supplier...',
                allowClear: true,
                width: '100%'
            });

            $('.product-select').select2({
                theme: 'bootstrap-5',
                placeholder: 'Cari produk...',
                allowClear: true,
                width: '100%'
            });

            $('.product-select').on('change', function() {

                if (isSyncing) return;

                const productId = $(this).val();

                if (!productId) return;

                pilihProduk(productId);

            });

            $('#qty, #harga').on('input', function() {

                hitungSubtotal();

            });

            function pilihProduk(productId) {

                isSyncing = true;

                $('#product_id').val(productId);

                $('#kode_produk').val(productId).trigger('change.select2');
                $('#nama_produk').val(productId).trigger('change.select2');
                $('#merk_produk').val(productId).trigger('change.select2');

                const selected =
                    $('#kode_produk option[value="' + productId + '"]');

                const harga = selected.data('harga') || 0;

                $('#harga').val(harga);

                hitungSubtotal();

                isSyncing = false;
            }

            function hitungSubtotal() {

                const qty = parseFloat($('#qty').val()) || 0;

                const harga = parseFloat($('#harga').val()) || 0;

                const subtotal = qty * harga;

                $('#subtotal').val(
                    subtotal.toLocaleString('id-ID')
                );

            }

            const initialProductId = $('#product_id').val();

            if (initialProductId) {

                pilihProduk(initialProductId);

            }

        });
    </script>
@endpush
