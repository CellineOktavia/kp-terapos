@extends('app.master')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <style>
        /* ==========================================
               TERAPOS SYSTEM UNIFIED DESIGN SYSTEM (GREEN)
               ========================================== */
        .card-terapos {
            border: 1px solid #f0f0f0 !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03) !important;
            background-color: #ffffff;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .card-terapos .card-header-green {
            background-color: #198754 !important;
            color: #ffffff;
            font-weight: 600;
            padding: 14px 20px;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
        }

        .label-title {
            font-size: 11px;
            font-weight: 700;
            color: #a0aec0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            display: block;
        }

        .form-control-terapos,
        .form-select-terapos {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 10px 14px;
            font-size: 14px;
            color: #2d3748;
            font-weight: 500;
            transition: all .2s;
        }

        .form-control-terapos:focus,
        .form-select-terapos:focus {
            border-color: #198754;
            box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.15);
            outline: none;
        }

        .form-control-readonly {
            background-color: #f8fafc !important;
            color: #64748b !important;
        }

        .btn-action-outline {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            color: #4a5568;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 8px 16px;
            border-radius: 10px;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-action-outline:hover {
            background-color: #f7fafc;
            color: #1a202c;
            border-color: #cbd5e0;
        }

        .btn-submit-terapos {
            background: linear-gradient(135deg, #198754, #20c997);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 700;
            width: 100%;
            transition: .25s;
            box-shadow: 0 4px 14px rgba(25, 135, 84, .25);
        }

        .btn-submit-terapos:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(25, 135, 84, .35);
        }

        .input-group-text-terapos {
            border-radius: 10px 0 0 10px;
            border: 1px solid #e2e8f0;
            border-end: none;
            background: #f8fafc;
            color: #64748b;
            font-weight: 600;
        }

        .input-group-terapos .form-control-terapos {
            border-radius: 0 10px 10px 0;
        }

        #reader {
            width: 100%;
            min-height: 240px;
            overflow: hidden;
            border: 2px dashed #cbd5e1;
            border-radius: 16px;
        }

        .table-terapos th {
            font-size: 11px;
            font-weight: 700;
            color: #a0aec0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #edf2f7;
            padding: 16px 14px;
            background-color: #f8fafc;
        }

        .table-terapos td {
            padding: 14px;
            border-bottom: 1px solid #f7fafc;
            color: #2d3748;
            vertical-align: middle;
        }

        .price-total-display {
            font-size: 1.8rem;
            font-weight: 800;
            color: #198754;
            line-height: 1.2;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-0">

        <!-- Header Halaman -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Tambah Penjualan</h3>
                <p class="text-muted small mb-0">Cari berdasarkan kode atau nama barang, lalu tambahkan ke keranjang.</p>
            </div>
            <div>
                <a href="{{ route('penjualan.index') }}" class="btn-action-outline">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Alert Error -->
        @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4"
                style="background-color: #f8d7da; color: #842029;">
                <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Terjadi kesalahan pada
                    inputan Anda:</div>
                <ul class="mb-0 ps-3 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('penjualan.store') }}" method="POST" id="pos-form">
            @csrf

            <div class="row g-4">

                <!-- KOLOM KIRI: INFORMASI TRANSAKSI & PEMBAYARAN -->
                <div class="col-lg-4">

                    <!-- Card Informasi Transaksi -->
                    <div class="card card-terapos">
                        <div class="card-header-green">
                            <i class="bi bi-info-circle-fill"></i> Informasi Transaksi
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label for="sale-number" class="label-title">No. Penjualan</label>
                                <input id="sale-number" type="text"
                                    class="form-control form-control-terapos form-control-readonly fw-bold text-success"
                                    value="{{ $generatedNumber }}" readonly>
                                <small class="text-muted d-block mt-1" style="font-size: 11px;">*Dibuat otomatis oleh
                                    sistem.</small>
                            </div>

                            <div class="mb-3">
                                <label for="tanggal" class="label-title">Tanggal</label>
                                <input id="tanggal" type="date" name="tanggal"
                                    class="form-control form-control-terapos"
                                    value="{{ old('tanggal', now()->toDateString()) }}" required>
                            </div>

                            <div class="mb-0">
                                <label class="label-title">User</label>
                                <input type="text" class="form-control form-control-terapos form-control-readonly"
                                    value="{{ auth()->user()->name }}" readonly>
                                <small class="text-muted d-block mt-1" style="font-size: 11px;">Role:
                                    {{ auth()->user()->display_role }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Card Ringkasan Pembayaran -->
                    <div class="card card-terapos">
                        <div class="card-header-green">
                            <i class="bi bi-wallet2"></i> Ringkasan Pembayaran
                        </div>
                        <div class="card-body p-4">
                            <div class="pb-3 mb-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small">Subtotal Barang</span>
                                    <span class="fw-semibold" id="sale-subtotal">Rp 0</span>
                                </div>
                                <div id="sale-discount-row" class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted small" id="sale-discount-label">Diskon</span>
                                    <span class="fw-semibold text-success" id="sale-discount">Rp 0</span>
                                </div>
                                <span class="label-title">Total Bayar</span>
                                <div class="price-total-display" id="sale-total">Rp 0</div>
                                <small class="text-muted d-block mt-2">Diskon 5% untuk pembelian di atas Rp300.000.</small>
                            </div>

                            <div class="mb-3">
                                <label for="bayar" class="label-title">Jumlah Bayar (Rp)</label>
                                <div class="input-group input-group-terapos">
                                    <span class="input-group-text input-group-text-terapos">Rp</span>
                                    <input id="bayar" type="text" inputmode="numeric" name="bayar"
                                        class="form-control form-control-terapos fw-bold text-dark border-start-0"
                                        value="{{ old('bayar') ? number_format((int) old('bayar'), 0, ',', '.') : '' }}" required placeholder="0">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="kembalian" class="label-title">Kembalian</label>
                                <input id="kembalian" type="text"
                                    class="form-control form-control-terapos form-control-readonly fw-bold text-success"
                                    value="Rp 0" readonly>
                            </div>

                            <button type="submit" class="btn-submit-terapos" id="submit-sale">
                                <i class="bi bi-check-circle-fill me-1"></i> Simpan Penjualan
                            </button>
                        </div>
                    </div>

                </div>

                <!-- KOLOM KANAN: SCANNER & KERANJANG -->
                <div class="col-lg-8">

                    <!-- Pencarian barang -->
                    <div class="card card-terapos">
                        <div class="card-header-green">
                            <i class="bi bi-search"></i> Cari Barang
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-8">
                                    <label for="product-search" class="label-title">Nama atau Kode Barang</label>
                                    <input id="product-search" type="search" list="product-search-list"
                                        class="form-control form-control-terapos"
                                        placeholder="Cari nama atau kode barang..." autocomplete="off">
                                    <datalist id="product-search-list">
                                        @foreach ($products as $product)
                                            @if ($product->stok > 0)
                                                <option value="{{ $product->kode_produk }} - {{ $product->nama_produk }}"></option>
                                            @endif
                                        @endforeach
                                    </datalist>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-success w-100 py-2 fw-semibold" id="add-search-product">
                                        <i class="bi bi-plus-lg me-1"></i> Tambahkan Barang
                                    </button>
                                </div>
                            </div>
                            <div id="scan-result" class="mt-3" role="status" aria-live="polite"></div>
                        </div>
                    </div>

                    <!-- Card Keranjang Belanja -->
                    <div class="card card-terapos">
                        <div class="card-header-green d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-cart-fill"></i> Keranjang Belanja
                            </div>
                            <button type="button" id="add-item"
                                class="btn btn-light btn-sm rounded-3 fw-bold text-success px-3">
                                <i class="bi bi-plus-lg me-1"></i> Tambah Baris
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-borderless table-terapos align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 230px" class="ps-4">Barang</th>
                                            <th class="text-center">Kode</th>
                                            <th class="text-center">Stok</th>
                                            <th style="width: 100px" class="text-center">Qty</th>
                                            <th class="text-end">Harga</th>
                                            <th class="text-end">Subtotal</th>
                                            <th class="text-center pe-4" style="width: 60px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cart-items"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </form>

        <!-- Template Baris Keranjang -->
        <template id="cart-row-template">
            <tr class="cart-row">
                <td class="ps-4 py-3">
                    <select class="form-select form-select-terapos cart-product" aria-label="Barang" required>
                        <option value="">Pilih barang...</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-code="{{ $product->kode_produk }}"
                                data-name="{{ $product->nama_produk }}" data-price="{{ $product->harga_jual }}"
                                data-stock="{{ $product->stok }}" @disabled($product->stok < 1)>
                                {{ $product->kode_produk }} - {{ $product->nama_produk }}
                                (Stok: {{ $product->stok }}
                                {{ $product->satuan }}{{ $product->stok < 1 ? ' - HABIS' : '' }})
                            </option>
                        @endforeach
                    </select>
                </td>
                <td class="cart-code text-center font-monospace small text-muted">-</td>
                <td class="cart-stock text-center fw-semibold text-dark">-</td>
                <td>
                    <input type="number" class="form-control form-control-terapos text-center cart-qty" min="1"
                        value="1" aria-label="Jumlah barang" required>
                </td>
                <td class="cart-price text-end text-muted">-</td>
                <td class="cart-subtotal text-end fw-bold text-dark">Rp 0</td>
                <td class="text-center pe-4">
                    <button type="button" class="btn btn-link text-danger p-0 remove-cart-item" title="Hapus Item">
                        <i class="bi bi-trash-fill fs-5"></i>
                    </button>
                </td>
            </tr>
        </template>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('pos-form');
            const cart = document.getElementById('cart-items');
            const rowTemplate = document.getElementById('cart-row-template');
            const productSearch = document.getElementById('product-search');
            const scanResult = document.getElementById('scan-result');
            const previousItems = @js(old('items', []));

            function currency(value) {
                return 'Rp ' + Number(value || 0).toLocaleString('id-ID', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 2
                });
            }

            function numericValue(value) {
                return Number(String(value || '').replace(/\D/g, '') || 0);
            }

            function notify(message, type) {
                scanResult.innerHTML = '';
                const alert = document.createElement('div');
                alert.className = 'alert alert-' + type + ' border-0 rounded-3 py-2 px-3 mb-0';
                alert.textContent = message;
                scanResult.appendChild(alert);
            }

            function reindexCart() {
                cart.querySelectorAll('.cart-row').forEach(function(row, index) {
                    row.querySelector('.cart-product').name = 'items[' + index + '][product_id]';
                    row.querySelector('.cart-qty').name = 'items[' + index + '][qty]';
                });
            }

            function calculateTotals() {
                let subtotalPenjualan = 0;
                cart.querySelectorAll('.cart-row').forEach(function(row) {
                    const select = row.querySelector('.cart-product');
                    const option = select.options[select.selectedIndex];
                    const qtyInput = row.querySelector('.cart-qty');
                    const price = Number(option?.dataset.price || 0);
                    const qty = Number(qtyInput.value || 0);
                    const stock = Number(option?.dataset.stock || 0);
                    qtyInput.max = stock || '';
                    const subtotal = price * qty;
                    subtotalPenjualan += subtotal;
                    row.querySelector('.cart-subtotal').textContent = currency(subtotal);
                });

                subtotalPenjualan = Math.round((subtotalPenjualan + Number.EPSILON) * 100) / 100;
                const discountPercent = subtotalPenjualan > 300000 ? 5 : 0;
                const discount = Math.round((subtotalPenjualan * discountPercent / 100 + Number.EPSILON) * 100) / 100;
                const total = Math.round((subtotalPenjualan - discount + Number.EPSILON) * 100) / 100;

                document.getElementById('sale-subtotal').textContent = currency(subtotalPenjualan);
                document.getElementById('sale-discount-label').textContent = discountPercent ?
                    'Diskon ' + discountPercent + '%' : 'Diskon';
                document.getElementById('sale-discount').textContent = currency(discount);
                document.getElementById('sale-discount-row').style.display =
                    discountPercent || subtotalPenjualan === 0 ? 'flex' : 'none';
                document.getElementById('sale-total').textContent = currency(total);
                const paid = numericValue(document.getElementById('bayar').value);
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

            function addProduct(productId) {
                const option = Array.from(rowTemplate.content.querySelectorAll('.cart-product option'))
                    .find(item => item.value === String(productId));
                if (!option) {
                    notify('Barang tidak ditemukan.', 'danger');
                    return false;
                }

                const stock = Number(option.dataset.stock || 0);
                if (stock < 1) {
                    notify('Stok barang habis.', 'warning');
                    return false;
                }

                const existingRow = Array.from(cart.querySelectorAll('.cart-row')).find(
                    row => row.querySelector('.cart-product').value === String(productId)
                );
                if (existingRow) {
                    notify('Barang tersebut sudah ada di keranjang.', 'warning');
                    existingRow.querySelector('.cart-qty').focus();
                    return false;
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
                        notify('Barang tersebut sudah ada di keranjang.', 'warning');
                    }
                    setRowProduct(row, false);
                    return;
                }

                const stock = Number(option.dataset.stock || 0);
                if (stock < 1) {
                    select.value = '';
                    notify('Stok barang habis.', 'warning');
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

            function searchProduct() {
                const search = productSearch.value.trim().toLocaleLowerCase('id-ID');
                if (!search) {
                    productSearch.focus();
                    return;
                }
                const options = Array.from(rowTemplate.content.querySelectorAll('.cart-product option'))
                    .filter(option => option.value && Number(option.dataset.stock) > 0);
                const exact = options.find(option =>
                    (option.dataset.code + ' - ' + option.dataset.name).toLocaleLowerCase('id-ID') === search
                    || option.dataset.code.toLocaleLowerCase('id-ID') === search
                    || option.dataset.name.toLocaleLowerCase('id-ID') === search
                );
                const matches = exact ? [exact] : options.filter(option =>
                    option.dataset.code.toLocaleLowerCase('id-ID').includes(search)
                    || option.dataset.name.toLocaleLowerCase('id-ID').includes(search)
                );
                if (matches.length !== 1) {
                    notify(matches.length ? 'Pencarian cocok dengan beberapa barang. Pilih nama lengkap dari saran.' : 'Barang tidak ditemukan.', matches.length ? 'warning' : 'danger');
                    return;
                }
                if (addProduct(matches[0].value)) {
                    notify(matches[0].dataset.name + ' ditambahkan ke keranjang.', 'success');
                    productSearch.value = '';
                    productSearch.focus();
                }
            }

            cart.addEventListener('change', function(event) {
                if (event.target.matches('.cart-product')) {
                    setRowProduct(event.target.closest('.cart-row'), true);
                }
            });

            cart.addEventListener('input', function(event) {
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

            cart.addEventListener('click', function(event) {
                const btn = event.target.closest('.remove-cart-item');
                if (!btn) return;
                const rows = cart.querySelectorAll('.cart-row');
                if (rows.length === 1) {
                    rows[0].querySelector('.cart-product').value = '';
                    rows[0].querySelector('.cart-qty').value = 1;
                    setRowProduct(rows[0], false);
                } else {
                    btn.closest('.cart-row').remove();
                    reindexCart();
                    calculateTotals();
                }
            });

            document.getElementById('add-item').addEventListener('click', function() {
                addRow();
            });

            document.getElementById('bayar').addEventListener('input', function() {
                const start = this.selectionStart;
                const digitsBefore = this.value.slice(0, start).replace(/\D/g, '').length;
                this.value = this.value.replace(/\D/g, '');
                this.value = this.value ? Number(this.value).toLocaleString('id-ID') : '';
                let position = 0;
                let digits = 0;
                while (position < this.value.length && digits < digitsBefore) {
                    if (/\d/.test(this.value[position])) digits++;
                    position++;
                }
                this.setSelectionRange(position, position);
                calculateTotals();
            });

            productSearch.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    searchProduct();
                }
            });
            document.getElementById('add-search-product').addEventListener('click', searchProduct);

            form.addEventListener('submit', function(event) {
                reindexCart();
                const rows = cart.querySelectorAll('.cart-row');
                const total = calculateTotals();
                if (!rows.length || Array.from(rows).some(row => !row.querySelector('.cart-product')
                    .value)) {
                    event.preventDefault();
                    notify('Pilih minimal satu barang sebelum menyimpan.', 'danger');
                    return;
                }
                if (numericValue(document.getElementById('bayar').value) < total) {
                    event.preventDefault();
                    notify('Jumlah pembayaran kurang dari total penjualan.', 'danger');
                    return;
                }
                document.getElementById('bayar').value = numericValue(document.getElementById('bayar').value);
                document.getElementById('submit-sale').disabled = true;
            });

            if (Array.isArray(previousItems) && previousItems.length) {
                previousItems.forEach(function(item) {
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

        });
    </script>
@endpush
