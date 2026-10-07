@extends('app.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">Nota Penjualan</h2>
                <p class="text-muted mb-0">TERAPOS</p>
            </div>
            <a href="{{ route('penjualan.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <strong>Nomor Transaksi</strong>
                        <p>{{ $penjualan->nomor_penjualan }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong>Tanggal</strong>
                        <p>{{ $penjualan->tanggal->format('d/m/Y') }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong>Pelanggan</strong>
                        <p>{{ $penjualan->customer->nama_customer ?? 'Pelanggan Umum' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header fw-bold">Detail Barang</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($penjualan->detailPenjualans as $detail)
                                <tr>
                                    <td>{{ $detail->product->kode_produk ?? '-' }}</td>
                                    <td>{{ $detail->product->nama_produk ?? 'Produk tidak tersedia' }}</td>
                                    <td>{{ $detail->qty }} {{ $detail->product->satuan ?? '' }}</td>
                                    <td>{{ $detail->qty }} × Rp{{ number_format($detail->harga, 0, ',', '.') }}</td>
                                    <td>Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="ms-auto" style="max-width: 360px">
                    <div class="d-flex justify-content-between">
                        <strong>Total</strong>
                        <strong>Rp{{ number_format($penjualan->total, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <span>Bayar</span>
                        <span>Rp{{ number_format($penjualan->bayar, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <span>Kembalian</span>
                        <span>Rp{{ number_format($penjualan->kembalian, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
