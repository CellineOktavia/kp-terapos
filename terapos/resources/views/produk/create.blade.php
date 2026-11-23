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
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            border: none;
            color: white;
            padding: 10px 24px;
            border-radius: 12px;
            font-weight: 600;
            transition: .25s;
            box-shadow: 0 4px 14px rgba(37, 99, 235, .25);
        }

        .btn-submit-terapos:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, .35);
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
                <h2 class="page-title">Tambah Barang</h2>
                <p class="page-subtitle">Tambahkan barang baru ke TERAPOS.</p>
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

        <form action="{{ route('produk.store') }}" method="POST">
            @csrf

            {{-- 1. INFORMASI UTAMA BARANG --}}
            <div class="card detail-card">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-box-seam-fill me-2"></i> Informasi Barang
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="kode_dasar" class="info-label">Kode Dasar</label>
                            <input id="kode_dasar" type="text" name="kode_dasar"
                                class="form-control form-control-terapos" value="{{ old('kode_dasar') }}"
                                placeholder="Contoh: snc3w" required>
                            <small class="text-muted d-block mt-1" style="font-size: .78rem;">Preview kode: <span
                                    id="kode-preview">-</span>. Kode final ditentukan server.</small>
                        </div>
                        <div class="col-md-6">
                            <label for="nama_produk" class="info-label">Nama Barang</label>
                            <input id="nama_produk" type="text" name="nama_produk" class="form-control form-control-terapos"
                                value="{{ old('nama_produk') }}" placeholder="Masukkan nama barang" required>
                            <div id="product-suggestions" class="list-group mt-2 small" role="status" aria-live="polite"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="category_id" class="info-label">Kategori</label>
                            <select id="category_id" name="category_id" class="form-select form-select-terapos">
                                <option value="">Tanpa kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                        {{ $category->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="merk" class="info-label">Merk</label>
                            <input id="merk" type="text" name="merk" class="form-control form-control-terapos"
                                value="{{ old('merk') }}" placeholder="Masukkan merk barang">
                        </div>
                        <div class="col-md-6">
                            <label for="satuan" class="info-label">Satuan</label>
                            <select id="satuan" name="satuan" class="form-select form-select-terapos" required>
                                @foreach (['pcs', 'pack', 'box', 'botol', 'kaleng', 'sachet', 'kg', 'gram', 'liter', 'ml', 'unit'] as $unit)
                                    <option value="{{ $unit }}" @selected(old('satuan', 'pcs') === $unit)>{{ $unit }}
                                    </option>
                                @endforeach
                            </select>
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
                            <div>
                                <label for="stok_minimum" class="info-label">Stok Minimum</label>
                                <input id="stok_minimum" type="number" name="stok_minimum" class="form-control form-control-terapos"
                                    value="{{ old('stok_minimum', 5) }}" min="0" required>
                                <small class="text-muted d-block mt-2" style="font-size: .8rem;">
                                    Batas minimum untuk peringatan stok menipis.
                                </small>
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
                                <label for="harga_beli" class="info-label">Harga Beli (Modal)</label>
                                <div class="input-group input-group-terapos">
                                    <span class="input-group-text input-group-text-terapos">Rp</span>
                                    <input id="harga_beli" type="text" inputmode="numeric" name="harga_beli" class="form-control form-control-terapos rupiah-input"
                                        value="{{ old('harga_beli') }}" required placeholder="0">
                                </div>
                                <small class="text-muted d-block mt-1" style="font-size: .78rem;">Harga modal per
                                    unit.</small>
                            </div>
                            <div>
                                <label for="harga_jual" class="info-label">Harga Jual</label>
                                <div class="input-group input-group-terapos">
                                    <span class="input-group-text input-group-text-terapos">Rp</span>
                                    <input id="harga_jual" type="text" inputmode="numeric" name="harga_jual"
                                        class="form-control form-control-terapos fw-bold text-primary"
                                        value="{{ old('harga_jual') }}" required
                                        placeholder="0">
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
                    <i class="bi bi-check-lg me-1"></i> Simpan Barang
                </button>
            </div>

        </form>

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const base = document.querySelector('[name="kode_dasar"]');
            const name = document.querySelector('[name="nama_produk"]');
            const buy = document.querySelector('[name="harga_beli"]');
            const sell = document.querySelector('[name="harga_jual"]');
            const form = buy.closest('form');
            const suggestions = document.getElementById('product-suggestions');
            const productUrl = @js(url('/produk'));
            const existingProducts = @js($existingProducts->map(fn ($product) => [
                'id' => $product->id,
                'code' => $product->kode_produk,
                'name' => $product->nama_produk,
                'brand' => $product->merk,
                'category' => $product->category?->nama_kategori,
                'price' => $product->harga_jual,
            ])->values());
            let currentMatches = [];
            const warning = document.createElement('div');
            warning.className = 'alert alert-warning mt-3 mb-0';
            warning.textContent = 'Peringatan: harga jual berada di bawah harga beli terakhir. Pastikan harga ini merupakan keputusan Owner/subsidi silang.';
            warning.hidden = true;
            sell.closest('.card-body').appendChild(warning);
            function format(input) {
                const digits = input.value.replace(/\D/g, '');
                input.value = digits ? Number(digits).toLocaleString('id-ID') : '';
            }
            function check() {
                warning.hidden = Number(sell.value.replace(/\D/g, '') || 0) >= Number(buy.value.replace(/\D/g, '') || 0);
            }
            function updateCodePreview() {
                const codeBase = base.value.toLowerCase().replace(/\s+/g, '');
                const price = Number(sell.value.replace(/\D/g, '') || 0);
                const suffix = (price / 1000).toFixed(3).replace(/\.?0+$/, '');
                const code = codeBase && price ? codeBase + '-' + suffix : '';
                document.getElementById('kode-preview').textContent = code || '-';
                updateSuggestions(code);
            }
            function updateSuggestions(code) {
                const nameTerm = name.value.trim().toLocaleLowerCase('id-ID');
                const codeTerm = base.value.trim().toLocaleLowerCase('id-ID').replace(/\s+/g, '');
                const generatedCode = code.toLocaleLowerCase('id-ID');
                suggestions.replaceChildren();
                currentMatches = [];

                if (nameTerm.length < 2 && codeTerm.length < 2) return;

                currentMatches = existingProducts.map(function(product) {
                    const productName = product.name.toLocaleLowerCase('id-ID');
                    const productCode = product.code.toLocaleLowerCase('id-ID');
                    let score = Infinity;
                    if (nameTerm.length >= 2 && productName === nameTerm) score = 0;
                    else if (generatedCode && productCode === generatedCode) score = 0;
                    else if (nameTerm.length >= 2 && productName.includes(nameTerm)) score = 1;
                    else if (codeTerm.length >= 2 && productCode.includes(codeTerm)) score = 2;
                    return score === Infinity ? null : { product, score };
                }).filter(Boolean).sort((a, b) => a.score - b.score).slice(0, 5);

                if (!currentMatches.length) {
                    const empty = document.createElement('div');
                    empty.className = 'text-muted';
                    empty.textContent = 'Tidak ditemukan nama atau kode barang yang mirip.';
                    suggestions.appendChild(empty);
                    return;
                }

                currentMatches.forEach(function(match) {
                    const product = match.product;
                    const item = document.createElement('a');
                    item.className = 'list-group-item list-group-item-action py-2';
                    item.href = productUrl + '/' + product.id;
                    const title = document.createElement('div');
                    title.className = 'fw-semibold';
                    title.textContent = product.code + ' - ' + product.name;
                    const details = document.createElement('div');
                    details.className = 'text-muted';
                    details.textContent = [product.brand, product.category, 'Rp ' +
                        Number(product.price).toLocaleString('id-ID')
                    ].filter(Boolean).join(' · ');
                    item.append(title, details);
                    suggestions.appendChild(item);
                });
            }
            [buy, sell].forEach(input => input.addEventListener('input', function() {
                format(this);
                check();
            }));
            [base, name, sell].forEach(input => input.addEventListener('input', updateCodePreview));
            form.addEventListener('submit', function(event) {
                const nameTerm = name.value.trim().toLocaleLowerCase('id-ID');
                const code = document.getElementById('kode-preview').textContent.toLocaleLowerCase('id-ID');
                const exactMatch = currentMatches.some(match =>
                    match.product.name.toLocaleLowerCase('id-ID') === nameTerm ||
                    match.product.code.toLocaleLowerCase('id-ID') === code
                );
                if (exactMatch) {
                    event.preventDefault();
                    window.alert('Nama atau kode barang yang sama sudah tersedia. Periksa rekomendasi sebelum menyimpan.');
                    return;
                }
                if (currentMatches.length && !window.confirm('Ditemukan produk dengan nama atau kode yang mirip. Tetap simpan barang baru?')) {
                    event.preventDefault();
                    return;
                }
                [buy, sell].forEach(input => input.value = input.value.replace(/\D/g, ''));
                form.querySelector('button[type="submit"]').disabled = true;
            });
            check();
            updateCodePreview();
        });
    </script>
@endpush
