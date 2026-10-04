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

        .info-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 15px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="mb-4">

            <h2 class="fw-bold">
                Edit Penjualan
            </h2>

            <p class="text-muted">
                Perbarui transaksi penjualan pada sistem SPARTA
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

        {{-- FORM --}}
        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <form action="{{ route('penjualan.update', $penjualan) }}" method="POST">

                    @csrf
                    @method('PUT')

                    {{-- HEADER TRANSAKSI --}}
                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-semibold">
                                Nomor Penjualan
                            </label>

                            <input type="text" class="form-control bg-light" value="{{ $penjualan->nomor_penjualan }}"
                                readonly>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-semibold">
                                Tanggal
                            </label>

                            <input type="date" name="tanggal" class="form-control"
                                value="{{ \Carbon\Carbon::parse($penjualan->tanggal)->format('Y-m-d') }}" required>

                        </div>

                    </div>

                    {{-- CUSTOMER --}}
                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            Customer
                        </label>

                        <select name="customer_id" class="form-select select2" required>

                            <option value="">
                                Pilih Customer
                            </option>

                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    {{ $customer->id == $penjualan->customer_id ? 'selected' : '' }}>

                                    {{ $customer->nama_customer }}

                                </option>
                            @endforeach

                        </select>

                    </div>

                    <hr>

                    <h5 class="fw-bold mb-3">
                        Detail Produk
                    </h5>

                    <input type="hidden" name="product_id" id="product_id" value="{{ $detail->product_id }}">

                    <div class="row">

                        {{-- KODE --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label fw-semibold">
                                Kode Produk
                            </label>

                            <select id="kode_produk" class="form-select product-select">

                                <option value="">
                                    Pilih Kode Produk
                                </option>

                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-harga="{{ $product->harga_jual }}"
                                        data-stok="{{ $product->stok }}"
                                        {{ $product->id == $detail->product_id ? 'selected' : '' }}>

                                        {{ $product->kode_produk }}

                                    </option>
                                @endforeach

                            </select>

                        </div>

                        {{-- NAMA --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label fw-semibold">
                                Nama Produk
                            </label>

                            <select id="nama_produk" class="form-select product-select">

                                <option value="">
                                    Pilih Nama Produk
                                </option>

                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-harga="{{ $product->harga_jual }}"
                                        data-stok="{{ $product->stok }}"
                                        {{ $product->id == $detail->product_id ? 'selected' : '' }}>

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
                                    <option value="{{ $product->id }}" data-harga="{{ $product->harga_jual }}"
                                        data-stok="{{ $product->stok }}"
                                        {{ $product->id == $detail->product_id ? 'selected' : '' }}>

                                        {{ $product->merk }}
                                        -
                                        {{ $product->nama_produk }}

                                    </option>
                                @endforeach

                            </select>

                        </div>

                    </div>

                    {{-- QTY --}}
                    <div class="row">

                        <div class="col-md-4 mb-3">

                            <label class="form-label fw-semibold">
                                Quantity
                            </label>

                            <input type="number" name="qty" id="qty" class="form-control"
                                value="{{ $detail->qty }}" min="1" required>

                        </div>

                    </div>

                    {{-- HARGA DAN TOTAL --}}
                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-semibold">
                                Harga Satuan
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    Rp
                                </span>

                                <input type="text" id="harga" class="form-control bg-light" readonly>

                            </div>

                        </div>

                        <div class="col-md-6 mb-3">

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

                        <a href="{{ route('penjualan.index') }}" class="btn btn-outline-secondary">

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

            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            $('.product-select').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            $('.product-select').on('change', function() {

                if (isSyncing) return;

                let productId = $(this).val();

                if (!productId) {
                    return;
                }

                pilihProduk(productId);

            });

            $('#qty').on('input', function() {

                hitungTotal();

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

                $('#harga').val(
                    Number(harga).toLocaleString('id-ID')
                );

                hitungTotal();

                isSyncing = false;
            }

            function hitungTotal() {

                const productId = $('#product_id').val();

                const selected =
                    $('#kode_produk option[value="' + productId + '"]');

                const harga = selected.data('harga') || 0;

                const qty = $('#qty').val() || 0;

                $('#subtotal').val(
                    Number(harga * qty)
                    .toLocaleString('id-ID')
                );
            }

            const initialProductId = $('#product_id').val();

            if (initialProductId) {

                pilihProduk(initialProductId);

            }

        });
    </script>
@endpush
