@extends('app.master')

@section('content')
    <style>
        /* ==========================================
           TERAPOS SYSTEM UNIFIED DESIGN SYSTEM (PURPLE THEME)
           ========================================== */
        .card-terapos {
            border: 1px solid #f0f0f0 !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03) !important;
            background-color: #ffffff;
            overflow: hidden;
        }

        /* Card Header Ungu Utama TERAPOS */
        .card-terapos .card-header-purple {
            background-color: #6f42c1 !important;
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
            transition: all 0.2s ease;
        }

        .form-control-terapos:focus,
        .form-select-terapos:focus {
            border-color: #6f42c1;
            box-shadow: 0 0 0 3px rgba(111, 66, 193, 0.15);
            outline: none;
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

        .btn-submit-purple {
            background: linear-gradient(135deg, #6f42c1, #8b5cf6);
            border: none;
            color: #ffffff;
            padding: 10px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 12px rgba(111, 66, 193, 0.2);
        }

        .btn-submit-purple:hover {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(111, 66, 193, 0.3);
        }

        .stock-info-box {
            background-color: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
            padding: 10px 14px;
            margin-top: 6px;
            font-size: 13px;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>

    <div class="container-fluid px-0">

        <!-- Header Halaman & Tombol Aksi -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Penyesuaian Stok</h3>
                <p class="text-muted small mb-0">Catat koreksi jumlah stok fisik barang dengan alasan yang jelas.</p>
            </div>
            <a href="{{ route('stok.index') }}" class="btn-action-outline">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Alert Notifikasi Error Validasi -->
        @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4"
                style="background-color: #f8d7da; color: #842029;">
                <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Terjadi kesalahan pada inputan
                    Anda:</div>
                <ul class="mb-0 ps-3 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Card Form Penyesuaian Stok -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card card-terapos">
                    <div class="card-header-purple">
                        <i class="bi bi-sliders"></i> Form Penyesuaian Stok
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('stok.adjustment.store') }}" method="POST">
                            @csrf

                            <!-- Pilih Produk -->
                            <div class="mb-4">
                                <label for="product_id" class="label-title">Pilih Produk</label>
                                <select id="product_id" name="product_id" class="form-select form-select-terapos" required>
                                    <option value="">Pilih produk...</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-stock="{{ $product->stok }}"
                                            data-unit="{{ $product->satuan }}" @selected(old('product_id') == $product->id)>
                                            {{ $product->kode_produk }} - {{ $product->nama_produk }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="stock-info-box">
                                    <i class="bi bi-info-circle-fill text-primary"></i>
                                    <span id="system-stock">Pilih produk untuk melihat stok sistem saat ini.</span>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <!-- Jenis Penyesuaian -->
                                <div class="col-md-6">
                                    <label for="jenis" class="label-title">Jenis Penyesuaian</label>
                                    <select id="jenis" name="jenis" class="form-select form-select-terapos" required>
                                        <option value="">Pilih jenis...</option>
                                        <option value="masuk" @selected(old('jenis') === 'masuk')>Tambah (+ Stok Masuk)</option>
                                        <option value="keluar" @selected(old('jenis') === 'keluar')>Kurang (- Stok Keluar)</option>
                                    </select>
                                </div>

                                <!-- Jumlah (Qty) -->
                                <div class="col-md-6">
                                    <label for="qty" class="label-title">Jumlah (Qty)</label>
                                    <input id="qty" type="number" name="qty"
                                        class="form-control form-control-terapos" value="{{ old('qty') }}" min="1"
                                        required placeholder="Masukkan jumlah unit">
                                </div>
                            </div>

                            <!-- Alasan Penyesuaian -->
                            <div class="mb-4">
                                <label for="alasan" class="label-title">Alasan Penyesuaian</label>
                                <input id="alasan" type="text" name="alasan"
                                    class="form-control form-control-terapos" value="{{ old('alasan') }}" maxlength="200"
                                    required placeholder="Contoh: Barang rusak, selisih opname, atau koreksi stok awal">
                            </div>

                            <!-- Tombol Submit & Batal -->
                            <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                                <a href="{{ route('stok.index') }}"
                                    class="btn btn-light px-4 py-2 rounded-3 fw-semibold border">
                                    Batal
                                </a>
                                <button type="submit" class="btn-submit-purple">
                                    <i class="bi bi-check-circle-fill"></i> Simpan Penyesuaian
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('product_id').addEventListener('change', function() {
            const option = this.selectedOptions[0];
            const text = option.value ?
                'Stok sistem saat ini: <strong>' + option.dataset.stock + ' ' + option.dataset.unit + '</strong>' :
                'Pilih produk untuk melihat stok sistem saat ini.';
            document.getElementById('system-stock').innerHTML = text;
        });
        document.getElementById('product_id').dispatchEvent(new Event('change'));
    </script>
@endpush
