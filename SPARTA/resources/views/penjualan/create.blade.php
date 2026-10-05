@extends('app.master')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <style>
        .scanner-card,
        .pos-card {
            border: 0;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .06);
        }

        #reader {
            width: 100%;
            min-height: 260px;
            overflow: hidden;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">Tambah Penjualan</h2>
                <p class="text-muted mb-0">Scan barcode atau pilih produk untuk ditambahkan ke keranjang.</p>
            </div>
            <a href="{{ route('penjualan.index') }}" class="btn btn-outline-secondary">Kembali</a>
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

        <div class="card scanner-card mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-8">
                        <label for="barcode-input" class="form-label">Cari / Scan Barcode atau Kode Produk</label>
                        <input id="barcode-input" type="text" class="form-control form-control-lg"
                            placeholder="Scan barcode atau ketik kode produk, lalu tekan Enter" autocomplete="off">
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-outline-primary" id="btn-camera">Buka Kamera</button>
                        <button type="button" class="btn btn-outline-secondary" id="btn-stop-camera" hidden>
                            Tutup Kamera
                        </button>
                    </div>
                </div>
                <div id="camera-area" class="mt-3" hidden>
                    <div id="reader"></div>
                </div>
                <div id="scan-result" class="mt-3" role="status" aria-live="polite"></div>
            </div>
        </div>

        <form action="{{ route('penjualan.store') }}" method="POST" id="pos-form">
            @csrf
            <div class="card pos-card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="sale-number" class="form-label">Nomor Transaksi</label>
                            <input id="sale-number" type="text" class="form-control"
                                value="{{ $generatedNumber }}" readonly>
                            <small class="text-muted">Dibuat otomatis oleh sistem.</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input id="tanggal" type="date" name="tanggal" class="form-control"
                                value="{{ old('tanggal', now()->toDateString()) }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="customer_id" class="form-label">Pelanggan (Opsional)</label>
                            <select id="customer_id" name="customer_id" class="form-select">
                                <option value="">Pelanggan Umum</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>
                                        {{ $customer->nama_customer }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Keranjang</h5>
                        <button type="button" id="add-item" class="btn btn-outline-primary btn-sm">
                            Tambah Produk
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th style="min-width: 280px">Produk</th>
                                    <th>Kode</th>
                                    <th>Stok</th>
                                    <th style="min-width: 100px">Qty</th>
                                    <th>Harga Jual</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="cart-items"></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-end">Total</th>
                                    <th id="sale-total">Rp 0</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-md-4 mb-3">
                            <label for="bayar" class="form-label">Bayar</label>
                            <input id="bayar" type="number" name="bayar" class="form-control"
                                value="{{ old('bayar') }}" min="0" step="0.01" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="kembalian" class="form-label">Kembalian</label>
                            <input id="kembalian" type="text" class="form-control" value="Rp 0" readonly>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" id="submit-sale">Simpan Penjualan</button>
                </div>
            </div>
        </form>

        <template id="cart-row-template">
            <tr class="cart-row">
                <td>
                    <select class="form-select cart-product" required>
                        <option value="">Pilih produk</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}"
                                data-code="{{ $product->kode_produk }}"
                                data-name="{{ $product->nama_produk }}"
                                data-price="{{ $product->harga_jual }}"
                                data-stock="{{ $product->stok }}"
                                @disabled($product->stok < 1)>
                                {{ $product->kode_produk }} - {{ $product->nama_produk }}
                                (Stok: {{ $product->stok }} {{ $product->satuan }}{{ $product->stok < 1 ? ' - HABIS' : '' }})
                            </option>
                        @endforeach
                    </select>
                </td>
                <td class="cart-code">-</td>
                <td class="cart-stock">-</td>
                <td><input type="number" class="form-control cart-qty" min="1" value="1" required></td>
                <td class="cart-price">-</td>
                <td class="cart-subtotal">Rp 0</td>
                <td>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-cart-item">Hapus</button>
                </td>
            </tr>
        </template>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('pos-form');
            const cart = document.getElementById('cart-items');
            const rowTemplate = document.getElementById('cart-row-template');
            const barcodeInput = document.getElementById('barcode-input');
            const scanResult = document.getElementById('scan-result');
            const previousItems = @js(old('items', []));
            let scanner = null;
            let cameraRunning = false;
            let scanLocked = false;

            function currency(value) {
                return 'Rp ' + Number(value || 0).toLocaleString('id-ID', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 2
                });
            }

            function notify(message, type) {
                scanResult.innerHTML = '';
                const alert = document.createElement('div');
                alert.className = 'alert alert-' + type + ' py-2 mb-0';
                alert.textContent = message;
                scanResult.appendChild(alert);
            }

            function reindexCart() {
                cart.querySelectorAll('.cart-row').forEach(function (row, index) {
                    row.querySelector('.cart-product').name = 'items[' + index + '][product_id]';
                    row.querySelector('.cart-qty').name = 'items[' + index + '][qty]';
                });
            }

            function calculateTotals() {
                let total = 0;
                cart.querySelectorAll('.cart-row').forEach(function (row) {
                    const select = row.querySelector('.cart-product');
                    const option = select.options[select.selectedIndex];
                    const qtyInput = row.querySelector('.cart-qty');
                    const price = Number(option?.dataset.price || 0);
                    const qty = Number(qtyInput.value || 0);
                    const stock = Number(option?.dataset.stock || 0);
                    qtyInput.max = stock || '';
                    const subtotal = price * qty;
                    total += subtotal;
                    row.querySelector('.cart-subtotal').textContent = currency(subtotal);
                });

                document.getElementById('sale-total').textContent = currency(total);
                const paid = Number(document.getElementById('bayar').value || 0);
                document.getElementById('kembalian').value = currency(Math.max(0, paid - total));
                return total;
            }

            function addRow(productId) {
                const row = rowTemplate.content.firstElementChild.cloneNode(true);
                const select = row.querySelector('.cart-product');
                select.value = productId || '';
                cart.appendChild(row);
                reindexCart();
                if (productId) {
                    setRowProduct(row, false);
                }
                return row;
            }

            function addProduct(productId, fromScan) {
                const option = Array.from(rowTemplate.content.querySelectorAll('.cart-product option'))
                    .find(item => item.value === String(productId));
                if (!option) {
                    notify('Produk tidak ditemukan.', 'danger');
                    return false;
                }

                const stock = Number(option.dataset.stock || 0);
                if (stock < 1) {
                    notify('Stok produk habis.', 'warning');
                    return false;
                }

                const existingRow = Array.from(cart.querySelectorAll('.cart-row')).find(
                    row => row.querySelector('.cart-product').value === String(productId)
                );
                if (existingRow) {
                    if (fromScan) {
                        const qty = existingRow.querySelector('.cart-qty');
                        if (Number(qty.value) + 1 > stock) {
                            notify('Stok ' + option.dataset.name + ' tidak mencukupi. Stok tersedia: ' + stock + '.', 'warning');
                            return false;
                        }
                        qty.value = Number(qty.value) + 1;
                        calculateTotals();
                    }
                    return true;
                }

                const blankRow = Array.from(cart.querySelectorAll('.cart-row')).find(
                    row => !row.querySelector('.cart-product').value
                );
                const row = blankRow || addRow();
                row.querySelector('.cart-product').value = String(productId);
                setRowProduct(row, true);
                return true;
            }

            function setRowProduct(row, reportDuplicate) {
                const select = row.querySelector('.cart-product');
                const productId = select.value;
                const option = select.options[select.selectedIndex];

                if (!productId) {
                    row.querySelector('.cart-code').textContent = '-';
                    row.querySelector('.cart-stock').textContent = '-';
                    row.querySelector('.cart-price').textContent = '-';
                    calculateTotals();
                    return;
                }

                const duplicate = Array.from(cart.querySelectorAll('.cart-product')).some(
                    other => other !== select && other.value === productId
                );
                if (duplicate) {
                    select.value = '';
                    if (reportDuplicate) {
                        notify('Produk tersebut sudah ada di keranjang.', 'warning');
                    }
                    setRowProduct(row, false);
                    return;
                }

                const stock = Number(option.dataset.stock || 0);
                if (stock < 1) {
                    select.value = '';
                    notify('Stok produk habis.', 'warning');
                    setRowProduct(row, false);
                    return;
                }

                row.querySelector('.cart-code').textContent = option.dataset.code;
                row.querySelector('.cart-stock').textContent = stock;
                row.querySelector('.cart-price').textContent = currency(option.dataset.price);
                const qty = row.querySelector('.cart-qty');
                qty.value = Math.min(Math.max(1, Number(qty.value || 1)), stock);
                calculateTotals();
            }

            async function lookupBarcode(value) {
                const code = value.trim();
                if (!code) return;

                try {
                    const response = await fetch('/api/produk-by-barcode?kode=' + encodeURIComponent(code), {
                        headers: { 'Accept': 'application/json' }
                    });
                    const data = await response.json();
                    if (!data.success) {
                        notify('Produk tidak ditemukan.', 'danger');
                        return;
                    }
                    if (Number(data.produk.stok) < 1) {
                        notify('Stok produk habis.', 'warning');
                        return;
                    }
                    if (addProduct(data.produk.id, true)) {
                        notify(data.produk.nama_produk + ' ditambahkan ke keranjang.', 'success');
                    }
                } catch (error) {
                    notify('Pencarian produk gagal. Silakan coba kembali.', 'danger');
                } finally {
                    barcodeInput.value = '';
                    barcodeInput.focus();
                }
            }

            cart.addEventListener('change', function (event) {
                if (event.target.matches('.cart-product')) {
                    setRowProduct(event.target.closest('.cart-row'), true);
                }
            });
            cart.addEventListener('input', function (event) {
                if (event.target.matches('.cart-qty')) {
                    const row = event.target.closest('.cart-row');
                    const option = row.querySelector('.cart-product').selectedOptions[0];
                    const stock = Number(option?.dataset.stock || 0);
                    if (Number(event.target.value) > stock) {
                        event.target.value = stock;
                        notify('Qty melebihi stok tersedia (' + stock + ').', 'warning');
                    }
                    calculateTotals();
                }
            });
            cart.addEventListener('click', function (event) {
                if (!event.target.matches('.remove-cart-item')) return;
                const rows = cart.querySelectorAll('.cart-row');
                if (rows.length === 1) {
                    rows[0].querySelector('.cart-product').value = '';
                    rows[0].querySelector('.cart-qty').value = 1;
                    setRowProduct(rows[0], false);
                } else {
                    event.target.closest('.cart-row').remove();
                    reindexCart();
                    calculateTotals();
                }
            });
            document.getElementById('add-item').addEventListener('click', function () {
                addRow();
            });
            document.getElementById('bayar').addEventListener('input', calculateTotals);
            barcodeInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    lookupBarcode(this.value);
                }
            });
            form.addEventListener('submit', function (event) {
                reindexCart();
                const rows = cart.querySelectorAll('.cart-row');
                const total = calculateTotals();
                if (!rows.length || Array.from(rows).some(row => !row.querySelector('.cart-product').value)) {
                    event.preventDefault();
                    notify('Pilih minimal satu produk sebelum menyimpan.', 'danger');
                    return;
                }
                if (Number(document.getElementById('bayar').value || 0) < total) {
                    event.preventDefault();
                    notify('Jumlah pembayaran kurang dari total penjualan.', 'danger');
                }
            });

            if (Array.isArray(previousItems) && previousItems.length) {
                previousItems.forEach(function (item) {
                    const row = addRow();
                    const select = row.querySelector('.cart-product');
                    select.value = String(item.product_id || '');
                    row.querySelector('.cart-qty').value = item.qty || 1;
                    setRowProduct(row, false);
                });
            } else {
                addRow();
            }
            reindexCart();
            calculateTotals();

            document.getElementById('btn-camera').addEventListener('click', function () {
                if (typeof Html5Qrcode === 'undefined') {
                    notify('Fitur kamera tidak tersedia.', 'danger');
                    return;
                }
                document.getElementById('camera-area').hidden = false;
                document.getElementById('btn-stop-camera').hidden = false;
                this.hidden = true;
                scanner = new Html5Qrcode('reader');
                scanner.start(
                    { facingMode: 'environment' },
                    { fps: 10, qrbox: { width: 300, height: 150 } },
                    function (decodedText) {
                        if (scanLocked) return;
                        scanLocked = true;
                        lookupBarcode(decodedText).finally(function () {
                            setTimeout(function () { scanLocked = false; }, 1200);
                        });
                    }
                ).then(function () {
                    cameraRunning = true;
                }).catch(function () {
                    notify('Kamera gagal dibuka. Periksa izin kamera perangkat.', 'danger');
                    document.getElementById('camera-area').hidden = true;
                    document.getElementById('btn-camera').hidden = false;
                    document.getElementById('btn-stop-camera').hidden = true;
                });
            });

            document.getElementById('btn-stop-camera').addEventListener('click', function () {
                if (cameraRunning && scanner) {
                    scanner.stop().finally(function () {
                        cameraRunning = false;
                        document.getElementById('camera-area').hidden = true;
                        document.getElementById('btn-camera').hidden = false;
                        this.hidden = true;
                    }.bind(this));
                }
            });
        });
    </script>
@endpush
