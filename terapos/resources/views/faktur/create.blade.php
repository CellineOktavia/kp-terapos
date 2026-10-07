@extends('app.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">Tambah Pembelian</h2>
                <p class="text-muted">Catat item pembelian. Stok produk bertambah setelah disimpan.</p>
            </div>
            <a href="{{ route('faktur.index') }}" class="btn btn-secondary">Kembali</a>
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

        @php($oldItems = old('items', [['product_id' => '', 'qty' => 1, 'harga' => '']]))

        <form action="{{ route('faktur.store') }}" method="POST" id="purchase-form">
            @csrf
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nomor-pembelian" class="form-label">Nomor Pembelian</label>
                            <input id="nomor-pembelian" type="text" class="form-control"
                                value="{{ $generatedNumber }}" readonly>
                            <small class="text-muted">Dibuat otomatis oleh sistem.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input id="tanggal" type="date" name="tanggal" class="form-control"
                                value="{{ old('tanggal', now()->toDateString()) }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Item Pembelian</h5>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="add-item">
                        Tambah Baris
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th style="min-width: 300px">Produk</th>
                                    <th>Kode</th>
                                    <th style="min-width: 100px">Qty</th>
                                    <th style="min-width: 160px">Harga Beli</th>
                                    <th style="min-width: 160px">Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="purchase-items">
                                @foreach ($oldItems as $index => $oldItem)
                                    <tr class="purchase-item">
                                        <td>
                                            <select name="items[{{ $index }}][product_id]"
                                                class="form-select product-select" required>
                                                <option value="">Pilih produk</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}"
                                                        data-code="{{ $product->kode_produk }}"
                                                        data-price="{{ $product->harga_beli }}"
                                                        @selected(($oldItem['product_id'] ?? '') == $product->id)>
                                                        {{ $product->kode_produk }} - {{ $product->nama_produk }}
                                                        (Stok: {{ $product->stok }} {{ $product->satuan }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="product-code text-muted">-</td>
                                        <td>
                                            <input type="number" name="items[{{ $index }}][qty]"
                                                class="form-control item-qty" min="1"
                                                value="{{ $oldItem['qty'] ?? 1 }}" required>
                                        </td>
                                        <td>
                                            <input type="number" name="items[{{ $index }}][harga]"
                                                class="form-control item-price" min="0" step="0.01"
                                                value="{{ $oldItem['harga'] ?? '' }}" required>
                                        </td>
                                        <td class="item-subtotal">Rp 0</td>
                                        <td>
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-item">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Total Pembelian</th>
                                    <th id="purchase-total">Rp 0</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Simpan Pembelian</button>
                </div>
            </div>
        </form>

        <template id="item-template">
            <tr class="purchase-item">
                <td>
                    <select class="form-select product-select" required>
                        <option value="">Pilih produk</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-code="{{ $product->kode_produk }}"
                                data-price="{{ $product->harga_beli }}">
                                {{ $product->kode_produk }} - {{ $product->nama_produk }}
                                (Stok: {{ $product->stok }} {{ $product->satuan }})
                            </option>
                        @endforeach
                    </select>
                </td>
                <td class="product-code text-muted">-</td>
                <td><input type="number" class="form-control item-qty" min="1" value="1" required></td>
                <td><input type="number" class="form-control item-price" min="0" step="0.01" required></td>
                <td class="item-subtotal">Rp 0</td>
                <td>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-item">Hapus</button>
                </td>
            </tr>
        </template>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tbody = document.getElementById('purchase-items');
            const template = document.getElementById('item-template');

            function formatCurrency(value) {
                return 'Rp ' + Number(value || 0).toLocaleString('id-ID', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 2
                });
            }

            function updateTotals() {
                let total = 0;
                tbody.querySelectorAll('.purchase-item').forEach(function (row) {
                    const qty = Number(row.querySelector('.item-qty').value || 0);
                    const price = Number(row.querySelector('.item-price').value || 0);
                    const subtotal = qty * price;
                    total += subtotal;
                    row.querySelector('.item-subtotal').textContent = formatCurrency(subtotal);
                });
                document.getElementById('purchase-total').textContent = formatCurrency(total);
            }

            function reindexRows() {
                tbody.querySelectorAll('.purchase-item').forEach(function (row, index) {
                    row.querySelector('.product-select').name = 'items[' + index + '][product_id]';
                    row.querySelector('.item-qty').name = 'items[' + index + '][qty]';
                    row.querySelector('.item-price').name = 'items[' + index + '][harga]';
                });
            }

            function setProductValue(row, preservePrice) {
                const select = row.querySelector('.product-select');
                const option = select.options[select.selectedIndex];
                const selectedId = select.value;
                row.querySelector('.product-code').textContent = selectedId ? option.dataset.code : '-';

                if (selectedId) {
                    const duplicate = Array.from(tbody.querySelectorAll('.product-select'))
                        .some(function (other) {
                            return other !== select && other.value === selectedId;
                        });
                    if (duplicate) {
                        alert('Produk yang sama tidak boleh dimasukkan dua kali.');
                        select.value = '';
                        row.querySelector('.product-code').textContent = '-';
                        row.querySelector('.item-price').value = '';
                        updateTotals();
                        return;
                    }
                    const priceInput = row.querySelector('.item-price');
                    if (!preservePrice || !priceInput.value) {
                        priceInput.value = option.dataset.price || 0;
                    }
                }
                updateTotals();
            }

            tbody.addEventListener('change', function (event) {
                if (event.target.matches('.product-select')) {
                    setProductValue(event.target.closest('.purchase-item'), false);
                }
            });
            tbody.addEventListener('input', function (event) {
                if (event.target.matches('.item-qty, .item-price')) {
                    updateTotals();
                }
            });
            tbody.addEventListener('click', function (event) {
                if (event.target.matches('.remove-item')) {
                    if (tbody.querySelectorAll('.purchase-item').length === 1) {
                        alert('Pembelian harus memiliki minimal satu item.');
                        return;
                    }
                    event.target.closest('.purchase-item').remove();
                    reindexRows();
                    updateTotals();
                }
            });

            document.getElementById('add-item').addEventListener('click', function () {
                const row = template.content.firstElementChild.cloneNode(true);
                tbody.appendChild(row);
                reindexRows();
            });

            reindexRows();
            tbody.querySelectorAll('.purchase-item').forEach(function (row) {
                setProductValue(row, true);
            });
            updateTotals();
        });
    </script>
@endpush
