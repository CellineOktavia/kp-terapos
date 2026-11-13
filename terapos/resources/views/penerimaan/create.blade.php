@extends('app.master')

@section('content')
    <style>
        /* ==========================================
               TERAPOS SYSTEM UNIFIED DESIGN SYSTEM
               ========================================== */
        .card-terapos {
            border: 1px solid #f0f0f0 !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03) !important;
            background-color: #ffffff;
            overflow: hidden;
        }

        .card-terapos .card-header-blue {
            background-color: #0d6efd !important;
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
        }

        .form-control-terapos:focus,
        .form-select-terapos:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
        }

        .form-control-terapos[readonly] {
            background-color: #f8fafc;
            color: #4a5568;
            font-weight: 600;
        }

        .btn-add-product {
            background: linear-gradient(135deg, #0d6efd, #3b82f6);
            border: none;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }

        .btn-add-product:hover {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(13, 110, 253, 0.3);
        }

        .btn-back-terapos {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            color: #4a5568;
            padding: 10px 18px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-back-terapos:hover {
            background-color: #f8fafc;
            color: #1a202c;
            border-color: #cbd5e0;
            transform: translateY(-1px);
        }

        .product-code-badge {
            background-color: #ebf8ff;
            color: #3182ce;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 8px;
            display: inline-block;
            font-size: 13px;
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
            padding: 16px 14px;
            border-bottom: 1px solid #f7fafc;
            color: #2d3748;
            vertical-align: middle;
        }

        .table-terapos tbody tr:hover {
            background-color: #f8fbff;
        }

        .action-btn {
            width: 38px;
            height: 38px;
            border: none;
            border-radius: 10px;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .btn-delete {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .btn-delete:hover {
            background-color: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }
    </style>

    <div class="container-fluid px-0">

        <!-- Header Halaman & Tombol Kembali -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Tambah Penerimaan Barang</h3>
                <p class="text-muted small mb-0">Catat barang fisik yang masuk agar stok diperbarui.</p>
            </div>
            <a href="{{ route('penerimaan.index') }}" class="btn-back-terapos">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Alert Validasi Error -->
        @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4 d-flex align-items-start gap-2"
                style="background-color: #f8d7da; color: #842029;">
                <i class="bi bi-exclamation-triangle-fill fs-5 mt-1"></i>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php($oldItems = old('items', [['product_id' => '', 'qty' => 1]]))

        <form action="{{ route('penerimaan.store') }}" method="POST" id="receipt-form">
            @csrf

            <!-- Card Informasi Penerimaan -->
            <div class="card card-terapos mb-4">
                <div class="card-header-blue">
                    <i class="bi bi-file-earmark-plus-fill"></i> Informasi Penerimaan Barang
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="receipt-number" class="label-title">No. Penerimaan</label>
                            <input id="receipt-number" type="text" class="form-control form-control-terapos"
                                value="{{ $generatedNumber }}" readonly>
                            <small class="text-muted d-block mt-1" style="font-size: 12px;">
                                <i class="bi bi-info-circle me-1"></i>Dibuat otomatis oleh sistem.
                            </small>
                        </div>
                        <div class="col-md-4">
                            <label for="tanggal" class="label-title">Tanggal Penerimaan</label>
                            <input id="tanggal" type="date" name="tanggal" class="form-control form-control-terapos"
                                value="{{ old('tanggal', now()->toDateString()) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="label-title">User Pencatat</label>
                            <input type="text" class="form-control form-control-terapos"
                                value="{{ auth()->user()->name }}" readonly>
                        </div>
                        <div class="col-12">
                            <label for="keterangan" class="label-title">Keterangan</label>
                            <textarea id="keterangan" name="keterangan" rows="2" class="form-control form-control-terapos"
                                placeholder="Contoh: Barang masuk dari supplier utama">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Daftar Barang Diterima -->
            <div class="card card-terapos">
                <div class="card-header-blue d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-box-seam-fill"></i>
                        Daftar Barang Diterima
                    </div>
                    <button type="button" class="btn btn-light text-primary fw-semibold btn-sm rounded-3 px-3 py-1"
                        id="add-item">
                        <i class="bi bi-plus-circle-fill me-1"></i> Tambah Baris
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-borderless table-terapos mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4" style="min-width: 300px">Barang</th>
                                    <th width="160">Kode Barang</th>
                                    <th width="160">Stok Saat Ini</th>
                                    <th style="min-width: 140px" width="160">Qty Diterima</th>
                                    <th class="text-center pe-4" width="90">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="receipt-items">
                                @foreach ($oldItems as $index => $oldItem)
                                    <tr class="receipt-item">
                                        <td class="ps-4">
                                            <select class="form-select form-select-terapos item-product" required>
                                                <option value="">Pilih barang...</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}"
                                                        data-code="{{ $product->kode_produk }}"
                                                        data-stock="{{ $product->stok }}"
                                                        data-unit="{{ $product->satuan }}" @selected(($oldItem['product_id'] ?? '') == $product->id)>
                                                        {{ $product->kode_produk }} - {{ $product->nama_produk }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="item-code text-muted">-</td>
                                        <td class="item-stock text-muted fw-medium">-</td>
                                        <td>
                                            <input type="number" class="form-control form-control-terapos item-qty"
                                                min="1" value="{{ $oldItem['qty'] ?? 1 }}" required>
                                        </td>
                                        <td class="text-center pe-4">
                                            <button type="button" class="action-btn btn-delete remove-item"
                                                title="Hapus Baris">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-top p-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('penerimaan.index') }}" class="btn-back-terapos">Batal</a>
                    <button type="submit" class="btn-add-product">
                        <i class="bi bi-check-circle-fill"></i> Simpan Penerimaan
                    </button>
                </div>
            </div>
        </form>

        <template id="receipt-item-template">
            <tr class="receipt-item">
                <td class="ps-4">
                    <select class="form-select form-select-terapos item-product" required>
                        <option value="">Pilih barang...</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-code="{{ $product->kode_produk }}"
                                data-stock="{{ $product->stok }}" data-unit="{{ $product->satuan }}">
                                {{ $product->kode_produk }} - {{ $product->nama_produk }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td class="item-code text-muted">-</td>
                <td class="item-stock text-muted fw-medium">-</td>
                <td>
                    <input type="number" class="form-control form-control-terapos item-qty" min="1"
                        value="1" required>
                </td>
                <td class="text-center pe-4">
                    <button type="button" class="action-btn btn-delete remove-item" title="Hapus Baris">
                        <i class="bi bi-trash-fill"></i>
                    </button>
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
                const codeCell = row.querySelector('.item-code');

                if (selected) {
                    codeCell.innerHTML = `<span class="product-code-badge">${option.dataset.code}</span>`;
                    row.querySelector('.item-stock').textContent =
                        Number(option.dataset.stock).toLocaleString('id-ID') + ' ' + option.dataset.unit;
                } else {
                    codeCell.textContent = '-';
                    row.querySelector('.item-stock').textContent = '-';
                }
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
