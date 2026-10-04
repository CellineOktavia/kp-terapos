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
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 1rem;
            font-weight: 600;
            color: #0f172a;
            margin: 0;
        }

        .info-group {
            margin-bottom: 24px;
        }

        .stock-good {
            background: rgba(16, 185, 129, .12);
            color: #059669;
            padding: 7px 14px;
            border-radius: 999px;
            font-size: .85rem;
            font-weight: 600;
            display: inline-block;
        }

        .stock-critical {
            background: rgba(239, 68, 68, .12);
            color: #dc2626;
            padding: 7px 14px;
            border-radius: 999px;
            font-size: .85rem;
            font-weight: 600;
            display: inline-block;
        }

        .price-highlight {
            font-size: 1.4rem;
            font-weight: 800;
            color: #2563eb;
        }

        .product-code-badge {
            background: rgba(37, 99, 235, .1);
            color: #2563eb;
            padding: 6px 14px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1rem;
            display: inline-block;
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

        .btn-edit-detail {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: .25s;
        }

        .btn-edit-detail:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(245, 158, 11, .3);
        }

        .divider {
            border-color: #f1f5f9;
            margin: 8px 0 24px;
        }

        /* ── Barcode ── */
        .barcode-box {
            background: #ffffff;
            border-radius: 16px;
            border: 1.5px solid #e2e8f0;
            padding: 20px 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        #barcodesvg {
            max-width: 100%;
            height: auto;
            display: block;
        }

        .barcode-kode {
            font-family: 'Courier New', monospace;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 4px;
            color: #1e293b;
            text-align: center;
        }

        .barcode-nama {
            font-size: .78rem;
            color: #94a3b8;
            font-weight: 500;
            text-align: center;
        }

        .btn-print-barcode {
            background: #fff;
            border: 1.5px dashed #cbd5e1;
            color: #475569;
            padding: 7px 16px;
            border-radius: 10px;
            font-size: .82rem;
            font-weight: 600;
            cursor: pointer;
            transition: .2s;
            margin-top: 2px;
        }

        .btn-print-barcode:hover {
            background: #f1f5f9;
            border-color: #94a3b8;
            color: #0f172a;
        }

        #barcode-error {
            display: none;
            font-size: .8rem;
            color: #dc2626;
            text-align: center;
            margin-top: 6px;
        }
    </style>

    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="page-header">
            <div>
                <h2 class="page-title">Detail Produk</h2>
                <p class="page-subtitle">Informasi lengkap produk</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('produk.index') }}" class="btn-back">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
                <a href="{{ route('produk.edit', $product->id) }}" class="btn-edit-detail">
                    <i class="bi bi-pencil-fill me-1"></i> Edit Produk
                </a>
            </div>
        </div>

        {{-- INFORMASI UTAMA + BARCODE --}}
        <div class="card detail-card">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-box-seam-fill me-2"></i>
                Informasi Produk
            </div>
            <div class="card-body">
                <div class="row">

                    {{-- Info produk kiri --}}
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6 info-group">
                                <p class="info-label">Kode Produk</p>
                                <span class="product-code-badge">{{ $product->kode_produk }}</span>
                            </div>
                            <div class="col-md-6 info-group">
                                <p class="info-label">Nama Produk</p>
                                <p class="info-value">{{ $product->nama_produk }}</p>
                            </div>
                            <div class="col-md-6 info-group">
                                <p class="info-label">Merk</p>
                                <p class="info-value">{{ $product->merk }}</p>
                            </div>
                            <div class="col-md-6 info-group">
                                <p class="info-label">Kategori</p>
                                <p class="info-value">{{ $product->kategori ?? '-' }}</p>
                            </div>
                            <div class="col-md-12 info-group mb-0">
                                <p class="info-label">Deskripsi</p>
                                <p class="info-value" style="font-weight:400; color:#475569; line-height:1.6;">
                                    {{ $product->deskripsi ?? 'Tidak ada deskripsi.' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Barcode kanan --}}
                    <div class="col-md-4 d-flex flex-column justify-content-center">
                        <p class="info-label text-center mb-2">Barcode Produk</p>
                        <div class="barcode-box" id="printBarcode">
                            {{-- Canvas barcode digenerate JS di bawah --}}
                            <svg id="barcodesvg"></svg>
                            <p id="barcode-error">Gagal membuat barcode.<br>Periksa kode produk.</p>
                            <div class="barcode-kode">{{ $product->kode_produk }}</div>
                            <div class="barcode-nama">{{ $product->nama_produk }}</div>
                        </div>
                        <div class="text-center mt-3">
                            <button onclick="printBarcode()" class="btn-print-barcode">
                                <i class="bi bi-printer me-1"></i> Print Barcode
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- STOK & HARGA --}}
        <div class="row">
            <div class="col-md-6">
                <div class="card detail-card">
                    <div class="card-header bg-success text-white">
                        <i class="bi bi-archive-fill me-2"></i>
                        Informasi Stok
                    </div>
                    <div class="card-body">
                        <div class="info-group">
                            <p class="info-label">Stok Saat Ini</p>
                            @if ($product->stok <= $product->stok_minimum)
                                <span class="stock-critical">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                    {{ $product->stok }} unit — Stok Kritis
                                </span>
                            @else
                                <span class="stock-good">
                                    <i class="bi bi-check-circle-fill me-1"></i>
                                    {{ $product->stok }} unit
                                </span>
                            @endif
                        </div>
                        <hr class="divider">
                        <div class="info-group mb-0">
                            <p class="info-label">Stok Minimum</p>
                            <p class="info-value">{{ $product->stok_minimum }} unit</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card detail-card">
                    <div class="card-header" style="background: linear-gradient(135deg, #7c3aed, #a78bfa); color: white;">
                        <i class="bi bi-tag-fill me-2"></i>
                        Informasi Harga
                    </div>
                    <div class="card-body">
                        <div class="info-group">
                            <p class="info-label">Harga Beli</p>
                            <p class="info-value">Rp {{ number_format($product->harga_beli ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <hr class="divider">
                        <div class="info-group mb-0">
                            <p class="info-label">Harga Jual</p>
                            <p class="price-highlight">Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SUPPLIER --}}
        @if ($product->supplier)
            <div class="card detail-card">
                <div class="card-header" style="background: linear-gradient(135deg, #0891b2, #38bdf8); color: white;">
                    <i class="bi bi-truck me-2"></i>
                    Informasi Supplier
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 info-group">
                            <p class="info-label">Kode Supplier</p>
                            <p class="info-value">{{ $product->supplier->kode_supplier }}</p>
                        </div>
                        <div class="col-md-4 info-group">
                            <p class="info-label">Nama Supplier</p>
                            <p class="info-value">{{ $product->supplier->nama_supplier }}</p>
                        </div>
                        <div class="col-md-4 info-group">
                            <p class="info-label">Telepon</p>
                            <p class="info-value">{{ $product->supplier->telepon }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection

@push('scripts')
    <script>
        // Muat JsBarcode secara dinamis, baru generate setelah loaded
        (function() {
            var script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js';
            script.onload = function() {
                generateBarcode();
            };
            script.onerror = function() {
                document.getElementById('barcode-error').style.display = 'block';
            };
            document.body.appendChild(script);
        })();

        function generateBarcode() {
            try {
                // Ambil kode produk dari atribut data agar aman dari karakter khusus
                var kode = document.getElementById('kode_produk_data').dataset.kode;

                JsBarcode('#barcodesvg', kode, {
                    format: 'CODE128',
                    width: 2,
                    height: 72,
                    displayValue: false,
                    margin: 8,
                    lineColor: '#1e293b',
                    background: '#ffffff',
                });
            } catch (e) {
                document.getElementById('barcodesvg').style.display = 'none';
                document.getElementById('barcode-error').style.display = 'block';
                console.error('JsBarcode error:', e);
            }
        }

        function printBarcode() {
            // Ambil SVG barcode yang sudah di-generate
            var svgEl = document.getElementById('barcodesvg');
            var kode = document.getElementById('kode_produk_data').dataset.kode;
            var nama = '{{ $product->nama_produk }}';
            var svgHtml = svgEl.outerHTML;

            // Buka jendela print baru yang bersih
            var win = window.open('', '_blank', 'width=400,height=300');
            win.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Barcode ${kode}</title>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    height: 100vh;
                    font-family: 'Courier New', monospace;
                    background: #fff;
                }
                svg {
                    max-width: 280px;
                    width: 100%;
                    display: block;
                }
                .kode {
                    font-size: 14px;
                    font-weight: 700;
                    letter-spacing: 4px;
                    color: #1e293b;
                    margin-top: 6px;
                }
                .nama {
                    font-size: 11px;
                    color: #64748b;
                    margin-top: 3px;
                }
                @media print {
                    @page { margin: 0; size: auto; }
                }
            </style>
        </head>
        <body>
            ${svgHtml}
            <div class="kode">${kode}</div>
            <div class="nama">${nama}</div>
        </body>
        </html>
    `);
            win.document.close();
            win.focus();
            // Tunggu konten render lalu print
            setTimeout(function() {
                win.print();
                win.close();
            }, 500);
        }
    </script>

    {{-- Simpan kode produk di data attribute — cara paling aman, bebas dari escaping/quoting issue --}}
    <span id="kode_produk_data" data-kode="{{ $product->kode_produk }}" style="display:none;" aria-hidden="true"></span>
@endpush
