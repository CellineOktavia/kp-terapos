@extends('app.master')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <style>
        .scanner-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .06);
            margin-bottom: 24px;
        }

        .scanner-card .card-header {
            padding: 16px 24px;
            font-weight: 700;
            font-size: 1rem;
            border: none;
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            color: white;
        }

        .scanner-card .card-body {
            padding: 24px;
        }

        .barcode-input-group .input-group-text {
            background: #f1f5f9;
            border-color: #e2e8f0;
            color: #64748b;
        }

        .barcode-input-group .form-control {
            height: 48px;
            border-color: #e2e8f0;
            border-radius: 0 12px 12px 0 !important;
        }

        .barcode-input-group .form-control:focus {
            border-color: #2563eb;
            box-shadow: none;
        }

        .barcode-input-group .input-group-text {
            border-radius: 12px 0 0 12px;
        }

        .btn-camera-open {
            height: 48px;
            border-radius: 12px;
            font-weight: 600;
            background: linear-gradient(135deg, #0891b2, #38bdf8);
            border: none;
            color: white;
            transition: .25s;
        }

        .btn-camera-open:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(8, 145, 178, .3);
        }

        .btn-camera-stop {
            border-radius: 10px;
            font-weight: 600;
        }

        #reader {
            border-radius: 14px;
            overflow: hidden;
            border: 2px dashed #cbd5e1;
            width: 100% !important;
            min-height: 300px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">Buat Penjualan</h2>
                <p class="text-muted">Transaksi penjualan barang</p>
            </div>
        </div>

        {{-- ============================================
             SCANNER BARCODE — disisipkan DI SINI,
             di atas card form utama
        ============================================ --}}
        <div class="card scanner-card">
            <div class="card-header">
                <i class="bi bi-upc-scan me-2"></i>
                Scan Barcode Produk
            </div>
            <div class="card-body">

                <div class="row g-3 align-items-center">

                    {{-- Input Manual --}}
                    <div class="col-md-8">
                        <label class="form-label fw-600 text-slate-600"
                            style="font-size:.8rem; font-weight:700; text-transform:uppercase;
                                      letter-spacing:.05em; color:#94a3b8;">
                            Kode Barcode
                        </label>
                        <div class="input-group barcode-input-group">
                            <span class="input-group-text">
                                <i class="bi bi-upc"></i>
                            </span>
                            <input type="text" id="barcode-input" class="form-control"
                                placeholder="Scan dengan alat fisik atau ketik kode produk, lalu Enter..."
                                autocomplete="off">
                        </div>
                        <small class="text-muted mt-1 d-block">
                            <i class="bi bi-info-circle me-1"></i>
                            Scanner fisik otomatis terdeteksi. Ketik manual lalu tekan
                            <kbd>Enter</kbd>
                        </small>
                    </div>

                    {{-- Tombol Kamera --}}
                    <div class="col-md-4">
                        <label class="form-label" style="visibility: hidden;">.</label>
                        <button type="button" class="btn btn-camera-open w-100" id="btn-camera" style="margin-top: -22px;">
                            <i class="bi bi-camera-fill me-2"></i>
                            Buka Kamera
                        </button>
                    </div>

                </div>

                {{-- Area Kamera --}}
                <div id="camera-area" class="mt-3" style="display: none;">
                    <div id="reader"></div>
                    <button type="button" class="btn btn-secondary btn-camera-stop mt-2" id="btn-stop-camera">
                        <i class="bi bi-x-circle me-1"></i> Tutup Kamera
                    </button>
                </div>

                {{-- Notifikasi hasil scan --}}
                <div id="scan-result" class="mt-3" style="display: none;"></div>

            </div>
        </div>
        {{-- ============================================
             AKHIR SCANNER BARCODE
        ============================================ --}}

        <div class="card shadow-sm">
            <div class="card-body">

                <form action="{{ route('penjualan.store') }}" method="POST">
                    @csrf

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pelanggan</label>
                            <select name="customer_id" class="form-select" required>
                                <option value="">Pilih Pelanggan</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">
                                        {{ $customer->nama_customer }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                    </div>

                    <div class="row">

                        <input type="hidden" name="product_id" id="product_id">

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Kode Produk</label>
                            <select id="kode_produk" class="form-select product-select" required>
                                <option value="">Pilih Kode Produk</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-harga="{{ $product->harga_jual }}"
                                        data-stok="{{ $product->stok }}" data-kode="{{ $product->kode_produk }}">
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
                                    <option value="{{ $product->id }}" data-harga="{{ $product->harga_jual }}"
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
                                    <option value="{{ $product->id }}" data-harga="{{ $product->harga_jual }}"
                                        data-stok="{{ $product->stok }}">
                                        {{ $product->merk }} - {{ $product->nama_produk }}
                                        ({{ $product->kode_produk }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Qty</label>
                            <input type="number" name="qty" id="qty" class="form-control" min="1"
                                required>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Harga</label>
                            <input type="text" id="harga" class="form-control" readonly>
                        </div>

                    </div>

                    <div class="mb-3">
                        <label class="form-label">Total</label>
                        <input type="text" id="subtotal" class="form-control" readonly>
                    </div>

                    <button class="btn btn-primary">Simpan Penjualan</button>
                    <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">Kembali</a>

                </form>

            </div>
        </div>

    </div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        let html5QrcodeScanner = null;
        let isCameraRunning = false;
        let sudahScan = false; // ← flag agar tidak scan berulang

        function tutupKamera() {
            if (isCameraRunning && html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    document.getElementById('camera-area').style.display = 'none';
                    document.getElementById('btn-camera').style.display = 'block';
                    isCameraRunning = false;
                    sudahScan = false;
                }).catch(() => {
                    isCameraRunning = false;
                    sudahScan = false;
                });
            }
        }

        function cariProdukDariBarcode(kode) {
            if (!kode.trim()) return;

            fetch(`/api/produk-by-barcode?kode=${encodeURIComponent(kode.trim())}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        pilihProdukDariScan(data.produk.id);
                        tampilkanNotifikasi('success',
                            `<i class="bi bi-check-circle-fill me-1"></i>
                         ${data.produk.nama_produk} berhasil dipilih`);

                        // Tutup kamera otomatis setelah scan berhasil
                        tutupKamera();
                    } else {
                        tampilkanNotifikasi('danger',
                            `<i class="bi bi-x-circle-fill me-1"></i>
                         Produk dengan kode "<strong>${kode}</strong>" tidak ditemukan`);
                    }
                    document.getElementById('barcode-input').value = '';
                    document.getElementById('barcode-input').focus();
                })
                .catch(() => {
                    tampilkanNotifikasi('danger', 'Gagal menghubungi server');
                });
        }

        function pilihProdukDariScan(productId) {
            $('#kode_produk').val(productId).trigger('change');
        }

        function tampilkanNotifikasi(type, pesan) {
            const el = document.getElementById('scan-result');
            el.style.display = 'block';
            el.innerHTML = `<div class="alert alert-${type} border-0 py-2 mb-0">${pesan}</div>`;
            setTimeout(() => {
                el.style.display = 'none';
            }, 3000);
        }

        // Input manual — tekan Enter
        document.getElementById('barcode-input').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                cariProdukDariBarcode(this.value);
            }
        });

        // Scanner fisik — buffer karakter cepat + Enter
        let barcodeBuffer = '';
        let barcodeTimer = null;
        document.addEventListener('keypress', function(e) {
            if (document.activeElement.id === 'barcode-input') return;
            const tag = document.activeElement.tagName;
            if (tag === 'INPUT' || tag === 'SELECT' || tag === 'TEXTAREA') return;

            clearTimeout(barcodeTimer);
            if (e.key !== 'Enter') {
                barcodeBuffer += e.key;
            } else if (barcodeBuffer.length > 3) {
                cariProdukDariBarcode(barcodeBuffer);
                barcodeBuffer = '';
                return;
            }
            barcodeTimer = setTimeout(() => {
                barcodeBuffer = '';
            }, 300);
        });

        // Kamera — buka
        document.getElementById('btn-camera').addEventListener('click', function() {
            document.getElementById('camera-area').style.display = 'block';
            this.style.display = 'none';
            sudahScan = false;

            html5QrcodeScanner = new Html5Qrcode('reader');
            html5QrcodeScanner.start({
                    facingMode: 'environment'
                }, {
                    fps: 10,
                    qrbox: {
                        width: 400,
                        height: 200
                    }, // ← diperbesar
                    aspectRatio: 1.7, // ← lebar penuh
                },
                (decodedText) => {
                    // Abaikan jika sudah scan sekali (cegah notifikasi berulang)
                    if (sudahScan) return;
                    sudahScan = true;
                    cariProdukDariBarcode(decodedText);
                }
            ).catch(err => {
                tampilkanNotifikasi('danger', 'Tidak dapat mengakses kamera: ' + err);
                document.getElementById('camera-area').style.display = 'none';
                document.getElementById('btn-camera').style.display = 'block';
            });
            isCameraRunning = true;
        });

        // Kamera — tutup manual
        document.getElementById('btn-stop-camera').addEventListener('click', tutupKamera);
    </script>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            let isSyncing = false;

            $('.product-select').select2({
                theme: 'bootstrap-5',
                placeholder: 'Cari produk...',
                allowClear: true,
                width: '100%'
            });

            $('select[name="customer_id"]').select2({
                theme: 'bootstrap-5',
                placeholder: 'Cari pelanggan...',
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

            $('#qty').on('input', function() {
                hitungTotal();
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

                $('#harga').val(Number(harga).toLocaleString('id-ID'));
                $('#qty').attr('max', stok).val(1); // otomatis isi qty 1 setelah scan
                hitungTotal();

                isSyncing = false;
            }

            function hitungTotal() {
                const productId = $('#product_id').val();
                if (!productId) {
                    $('#subtotal').val('');
                    return;
                }

                const selected = $('#kode_produk option[value="' + productId + '"]');
                const harga = selected.data('harga') || 0;
                const qty = $('#qty').val() || 0;

                $('#subtotal').val(Number(harga * qty).toLocaleString('id-ID'));
            }

            function clearProduct() {
                isSyncing = true;

                $('#product_id').val('');
                $('#kode_produk').val('').trigger('change.select2');
                $('#nama_produk').val('').trigger('change.select2');
                $('#merk_produk').val('').trigger('change.select2');

                $('#harga').val('');
                $('#subtotal').val('');
                $('#qty').val('').removeAttr('max');

                isSyncing = false;
            }
        });
    </script>
@endpush
