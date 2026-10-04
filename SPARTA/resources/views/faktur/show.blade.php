@extends('app.master')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="fw-bold">
                    Detail Faktur
                </h2>

                <p class="text-muted">
                    Informasi lengkap transaksi
                </p>

            </div>
            <a href="{{ route('faktur.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </div>

        {{-- Informasi Faktur --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                Informasi Faktur
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <strong>No Faktur</strong>
                        <p>
                            {{ $faktur->nomor_faktur }}
                        </p>
                    </div>
                    <div class="col-md-4">
                        <strong>Tanggal</strong>
                        <p>
                            {{ $faktur->tanggal }}
                        </p>
                    </div>
                    <div class="col-md-4">
                        <strong>Supplier</strong>
                        <p>
                            @if ($faktur->supplier)
                                {{ $faktur->supplier?->nama_supplier ?? '-' }}
                            @else
                                <span class="text-danger">
                                    Supplier tidak ditemukan
                                </span>
                            @endif
                        </p>

                    </div>

                </div>

            </div>

        </div>

        {{-- Detail Produk --}}
        <div class="card shadow-sm">

            <div class="card-header">

                Detail Produk

            </div>

            <div class="card-body">

                <div class="table-responsive">

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

                            @foreach ($faktur->detailFakturs as $detail)
                                <tr>

                                    <td>

                                        {{ $detail->product->nama_produk }}

                                    </td>

                                    <td>

                                        {{ $detail->qty }}

                                    </td>

                                    <td>

                                        Rp
                                        {{ number_format($detail->harga, 0, ',', '.') }}

                                    </td>

                                    <td>

                                        Rp
                                        {{ number_format($detail->subtotal, 0, ',', '.') }}

                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>

                <div class="text-end mt-4">

                    <h4 class="fw-bold">

                        Total:
                        Rp
                        {{ number_format($faktur->total, 0, ',', '.') }}

                    </h4>

                </div>

            </div>

        </div>

    </div>
@endsection
