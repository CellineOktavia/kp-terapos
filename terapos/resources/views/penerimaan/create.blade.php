@extends('app.master')

@section('content')
    <div class="container-fluid px-0">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Tambah Penerimaan Barang</h3>
                <p class="text-muted small mb-0">Catat barang fisik yang masuk agar stok diperbarui.</p>
            </div>
            <a href="{{ route('penerimaan.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
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

        @php($oldItems = old('items', [['product_id' => '', 'qty' => 1]]))

        <form action="{{ route('penerimaan.store') }}" method="POST" id="receipt-form">
            @csrf
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="receipt-number" class="form-label">No. Penerimaan</label>
                            <input id="receipt-number" type="text" class="form-control"
                                value="{{ $generatedNumber }}" readonly>
                            <small class="text-muted">Dibuat otomatis oleh sistem.</small>
                        </div>
                        <div class="col-md-4">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input id="tanggal" type="date" name="tanggal" class="form-control"
                                value="{{ old('tanggal', now()->toDateString()) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">User</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                        </div>
                        <div class="col-12">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea id="keterangan" name="keterangan" rows="2" class="form-control"
                                placeholder="Contoh: Barang masuk">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Barang</h5>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="add-item">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Baris
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th style="min-width: 300px">Barang</th>
                                    <th>Kode Barang</th>
                                    <th>Stok Saat Ini</th>
                                    <th style="min-width: 120px">Qty Diterima</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="receipt-items">
                                @foreach ($oldItems as $index => $oldItem)
                                    <tr class="receipt-item">
                                        <td>
                                            <select class="form-select item-product" required>
                                                <option value="">Pilih barang</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}"
                                                        data-code="{{ $product->kode_produk }}"
                                                        data-stock="{{ $product->stok }}"
                                                        data-unit="{{ $product->satuan }}"
                                                        @selected(($oldItem['product_id'] ?? '') == $product->id)>
                                                        {{ $product->kode_produk }} - {{ $product->nama_produk }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="item-code text-muted">-</td>
                                        <td class="item-stock text-muted">-</td>
                                        <td>
                                            <input type="number" class="form-control item-qty" min="1"
                                                value="{{ $oldItem['qty'] ?? 1 }}" required>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-item">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('penerimaan.index') }}" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Simpan Penerimaan
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <template id="receipt-item-template">
            <tr class="receipt-item">
                <td>
                    <select class="form-select item-product" required>
                        <option value="">Pilih barang</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-code="{{ $product->kode_produk }}"
                                data-stock="{{ $product->stok }}" data-unit="{{ $product->satuan }}">
                                {{ $product->kode_produk }} - {{ $product->nama_produk }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td class="item-code text-muted">-</td>
                <td class="item-stock text-muted">-</td>
                <td><input type="number" class="form-control item-qty" min="1" value="1" required></td>
                <td>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-item">Hapus</button>
                </td>
            </tr>
        </template>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('receipt-form');
            const tbody = document.getElementById('receipt-items');
            const template = document.getElementById('receipt-item-template');

            function updateRow(row) {
                const select = row.querySelector('.item-product');
                const option = select.selectedOptions[0];
                const selected = Boolean(select.value);
                row.querySelector('.item-code').textContent = selected ? option.dataset.code : '-';
                row.querySelector('.item-stock').textContent = selected ?
                    option.dataset.stock + ' ' + option.dataset.unit : '-';
            }

            function reindexRows() {
                tbody.querySelectorAll('.receipt-item').forEach(function(row, index) {
                    row.querySelector('.item-product').name = 'items[' + index + '][product_id]';
                    row.querySelector('.item-qty').name = 'items[' + index + '][qty]';
                });
            }

            tbody.addEventListener('change', function(event) {
                if (event.target.matches('.item-product')) {
                    updateRow(event.target.closest('.receipt-item'));
                }
            });

            tbody.addEventListener('click', function(event) {
                const button = event.target.closest('.remove-item');
                if (!button) return;

                const rows = tbody.querySelectorAll('.receipt-item');
                const row = button.closest('.receipt-item');
                if (rows.length === 1) {
                    row.querySelector('.item-product').value = '';
                    row.querySelector('.item-qty').value = 1;
                    updateRow(row);
                } else {
                    row.remove();
                    reindexRows();
                }
            });

            document.getElementById('add-item').addEventListener('click', function() {
                tbody.appendChild(template.content.firstElementChild.cloneNode(true));
                reindexRows();
            });

            form.addEventListener('submit', reindexRows);
            tbody.querySelectorAll('.receipt-item').forEach(updateRow);
            reindexRows();
        });
    </script>
@endpush
