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
                    Tambah Faktur
                </h2>

                <p class="text-muted">
                    Buat transaksi pembelian baru
                </p>

            </div>

            <a href="{{ route('faktur.index') }}" class="btn btn-secondary">

                Kembali

            </a>

        </div>

        <div class="card shadow-sm">

            <div class="card-body">

                <form action="{{ route('faktur.store') }}" method="POST">

                    @csrf

                    <div class="row">

                        {{-- Nomor Faktur --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Nomor Faktur

                            </label>

                            <input type="text" name="nomor_faktur" class="form-control"
                                value="INV-{{ now()->format('YmdHis') }}" readonly>

                        </div>

                        {{-- Tanggal --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Tanggal

                            </label>

                            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>

                        </div>

                        {{-- Supplier --}}
                        <div class="col-md-12 mb-3">

                            <label class="form-label">

                                Supplier

                            </label>

                            <select name="supplier_id" class="form-select" required>

                                <option value="">

                                    Pilih Supplier

                                </option>

                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">

                                        {{ $supplier->nama_supplier }}

                                    </option>
                                @endforeach

                            </select>

                        </div>

                    </div>

                    <hr>

                    <h5 class="fw-bold mb-3">

                        Detail Produk

                    </h5>

                    <div class="row">

                        <input type="hidden" name="product_id" id="product_id">

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Kode Produk</label>

                            <select id="kode_produk" class="form-select product-select" required>
                                <option value="">Pilih Kode Produk</option>

                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-harga="{{ $product->harga_beli }}"
                                        data-stok="{{ $product->stok }}">
                                        {{ $product->kode_produk }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Nama Produk</label>

                            <select id="nama_produk" class="form-select product-select" required>
                                <option value="">Pilih Nama Produk</option>

                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-harga="{{ $product->harga_beli }}"
                                        data-stok="{{ $product->stok }}">
                                        {{ $product->nama_produk }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Merk</label>

                            <select id="merk_produk" class="form-select product-select" required>
                                <option value="">Pilih Merk</option>

                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-harga="{{ $product->harga_beli }}"
                                        data-stok="{{ $product->stok }}">
                                        {{ $product->merk }} - {{ $product->nama_produk }} ({{ $product->kode_produk }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Qty --}}
                        <div class="col-md-2 mb-3">

                            <label class="form-label">

                                Qty

                            </label>

                            <input type="number" name="qty" id="qty" class="form-control" min="1"
                                required>

                        </div>

                        {{-- Harga --}}
                        <div class="col-md-3 mb-3">

                            <label class="form-label">

                                Harga

                            </label>

                            <input type="number" name="harga" id="harga" class="form-control" min="1"
                                required>

                        </div>

                        {{-- Subtotal --}}
                        <div class="col-md-3 mb-3">

                            <label class="form-label">

                                Subtotal

                            </label>

                            <input type="text" id="subtotal" class="form-control" readonly>

                        </div>

                    </div>

                    <hr>

                    <div class="text-end">

                        <button type="submit" class="btn btn-primary">

                            Simpan Faktur

                        </button>

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
                placeholder: 'Cari data...',
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

                if (!productId) {
                    clearProduct();
                    return;
                }

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

                const selected = $('#kode_produk option[value="' + productId + '"]');

                const harga = selected.data('harga') || 0;
                const stok = selected.data('stok') || 0;

                $('#harga').val(harga);
                $('#qty').attr('max', stok);

                hitungSubtotal();

                isSyncing = false;
            }

            function hitungSubtotal() {
                const qty = Number($('#qty').val() || 0);
                const harga = Number($('#harga').val() || 0);

                $('#subtotal').val(Number(qty * harga).toLocaleString('id-ID'));
            }

            function clearProduct() {
                isSyncing = true;

                $('#product_id').val('');
                $('#kode_produk').val('').trigger('change.select2');
                $('#nama_produk').val('').trigger('change.select2');
                $('#merk_produk').val('').trigger('change.select2');

                $('#harga').val('');
                $('#subtotal').val('');
                $('#qty').val('');
                $('#qty').removeAttr('max');

                isSyncing = false;
            }
        });
    </script>
@endpush
