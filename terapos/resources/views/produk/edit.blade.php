@extends('app.master')

@section('content')
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .page-subtitle {
            color: #64748b;
            margin: 0;
        }

        .detail-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .06);
            margin-bottom: 24px;
            background: #ffffff;
        }

        .detail-card .card-header {
            padding: 18px 24px;
            font-weight: 700;
            font-size: 1rem;
            border: none;
        }

        .detail-card .card-body {
            padding: 28px;
        }

        .info-label {
            font-size: .8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #94a3b8;
            margin-bottom: 6px;
            display: block;
        }

        .form-control-terapos,
        .form-select-terapos {
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            padding: 10px 16px;
            font-size: .95rem;
            color: #0f172a;
            font-weight: 500;
            transition: all .2s;
        }

        .form-control-terapos:focus,
        .form-select-terapos:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .1);
            outline: none;
        }

        .form-control-readonly {
            background-color: #f8fafc !important;
            color: #64748b !important;
        }

        .btn-back {
            background: #f1f5f9;
            border: none;
            color: #475569;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: .25s;
        }

        .btn-back:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .btn-submit-terapos {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            border: none;
            color: white;
            padding: 10px 24px;
            border-radius: 12px;
            font-weight: 600;
            transition: .25s;
            box-shadow: 0 4px 14px rgba(245, 158, 11, .25);
        }

        .btn-submit-terapos:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(245, 158, 11, .35);
        }

        .input-group-text-terapos {
            border-radius: 12px 0 0 12px;
            border: 1.5px solid #e2e8f0;
            border-end: none;
            background: #f8fafc;
            color: #64748b;
            font-weight: 600;
        }

        .input-group-terapos .form-control-terapos {
            border-radius: 0 12px 12px 0;
        }
    </style>

    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="page-header">
            <div>
                <h2 class="page-title">Edit Produk</h2>
                <p class="page-subtitle">Perbarui informasi produk TERAPOS.</p>
            </div>
            <div>
                <a href="{{ route('produk.index') }}" class="btn-back">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        {{-- ERROR VALIDATION --}}
        @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4"
                style="background: rgba(239, 68, 68, .12); color: #dc2626;">
                <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Terjadi kesalahan pada inputan
                    Anda:</div>
                <ul class="mb-0 ps-3 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('produk.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- 1. INFORMASI UTAMA PRODUK --}}
            <div class="card detail-card">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-box-seam-fill me-2"></i> Informasi Produk
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="info-label">Kode Produk</label>
                            <input type="text" name="kode_produk"
                                class="form-control form-control-terapos form-control-readonly"
                                value="{{ $product->kode_produk }}" required readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="info-label">Barcode Produk</label>
                            <input type="text" name="barcode"
                                class="form-control form-control-terapos form-control-readonly"
                                value="{{ $product->barcode }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="info-label">Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control form-control-terapos"
                                value="{{ old('nama_produk', $product->nama_produk) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="info-label">Kategori</label>
                            <select name="category_id" class="form-select form-select-terapos">
                                <option value="">Tanpa kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                                        {{ $category->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="info-label">Merk</label>
                            <input type="text" name="merk" class="form-control form-control-terapos"
                                value="{{ old('merk', $product->merk) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="info-label">Satuan</label>
                            <select name="satuan" class="form-select form-select-terapos" required>
                                @php($units = ['pcs', 'pack', 'box', 'botol', 'kaleng', 'sachet', 'kg', 'gram', 'liter', 'ml', 'unit'])
                                @if ($product->satuan && !in_array($product->satuan, $units, true))
                                    <option value="{{ $product->satuan }}" @selected(old('satuan', $product->satuan) === $product->satuan)>
                                        {{ $product->satuan }} (satuan saat ini)
                                    </option>
                                @endif
                                @foreach ($units as $unit)
                                    <option value="{{ $unit }}" @selected(old('satuan', $product->satuan ?: 'pcs') === $unit)>
                                        {{ $unit }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="info-label">Deskripsi</label>
                            <textarea name="deskripsi" rows="3" class="form-control form-control-terapos">{{ old('deskripsi', $product->deskripsi) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. INFORMASI STOK & HARGA (2 KOLOM) --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="card detail-card h-100 mb-0 mb-md-4">
                        <div class="card-header bg-success text-white">
                            <i class="bi bi-archive-fill me-2"></i> Informasi Stok
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="info-label">Stok Saat Ini</label>
                                <input type="text"
                                    class="form-control form-control-terapos form-control-readonly fw-bold"
                                    value="{{ $product->stok }} {{ $product->satuan }}" readonly>
                                <small class="text-muted d-block mt-1" style="font-size: .78rem;">
                                    *Stok hanya dapat berubah melalui Penjualan atau Penyesuaian Stok.
                                </small>
                            </div>
                            <div>
                                <label class="info-label">Stok Minimum</label>
                                <input type="number" name="stok_minimum" class="form-control form-control-terapos"
                                    value="{{ old('stok_minimum', $product->stok_minimum) }}" min="0" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card detail-card h-100 mb-4">
                        <div class="card-header"
                            style="background: linear-gradient(135deg, #7c3aed, #a78bfa); color: white;">
                            <i class="bi bi-tag-fill me-2"></i> Informasi Harga
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="info-label">Harga Beli (Modal)</label>
                                <div class="input-group input-group-terapos">
                                    <span class="input-group-text input-group-text-terapos">Rp</span>
                                    <input type="number" name="harga_beli" class="form-control form-control-terapos"
                                        value="{{ old('harga_beli', $product->harga_beli) }}" min="0" step="0.01"
                                        required>
                                </div>
                                <small class="text-muted d-block mt-1" style="font-size: .78rem;">Harga modal per
                                    unit.</small>
                            </div>
                            <div>
                                <label class="info-label">Harga Jual</label>
                                <div class="input-group input-group-terapos">
                                    <span class="input-group-text input-group-text-terapos">Rp</span>
                                    <input type="number" name="harga_jual"
                                        class="form-control form-control-terapos fw-bold text-primary"
                                        value="{{ old('harga_jual', $product->harga_jual) }}" min="0"
                                        step="0.01" required>
                                </div>
                                <small class="text-muted d-block mt-1" style="font-size: .78rem;">Harga jual ke
                                    konsumen.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TOMBOL SUBMIT --}}
            <div class="d-flex justify-content-end gap-2 mt-2">
                <a href="{{ route('produk.index') }}" class="btn-back">Batal</a>
                <button type="submit" class="btn-submit-terapos">
                    <i class="bi bi-pencil-fill me-1"></i> Simpan Perubahan
                </button>
            </div>

        </form>

    </div>
@endsection
