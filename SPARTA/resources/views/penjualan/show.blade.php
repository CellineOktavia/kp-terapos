@extends('app.master')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="fw-bold">

                    Detail Penjualan

                </h2>

                <p class="text-muted">

                    Informasi transaksi penjualan

                </p>

            </div>

            <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">

                Kembali

            </a>

        </div>

        <div class="card shadow-sm mb-4">

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6">

                        <p>

                            <strong>No Penjualan:</strong>

                            {{ $penjualan->nomor_penjualan }}

                        </p>

                        <p>

                            <strong>Pelanggan:</strong>

                            {{ $penjualan->customer->nama_customer }}

                        </p>

                    </div>

                    <div class="col-md-6">

                        <p>

                            <strong>Tanggal:</strong>

                            {{ $penjualan->tanggal }}

                        </p>

                        <p>

                            <strong>Total:</strong>

                            Rp {{ number_format($penjualan->total, 0, ',', '.') }}

                        </p>

                    </div>

                </div>

            </div>

        </div>

        <div class="card shadow-sm">

            <div class="card-header">

                Produk Yang Dijual

            </div>

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Subtotal</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($penjualan->detailPenjualans as $detail)
                            <tr>

                                <td>

                                    {{ $detail->product->nama_produk }}

                                </td>

                                <td>

                                    {{ $detail->qty }}

                                </td>

                                <td>

                                    Rp {{ number_format($detail->harga, 0, ',', '.') }}

                                </td>

                                <td>

                                    Rp {{ number_format($detail->subtotal, 0, ',', '.') }}

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>
@endsection
