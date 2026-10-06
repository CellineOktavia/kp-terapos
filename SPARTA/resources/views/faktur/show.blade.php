@extends('app.master')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="fw-bold">
                    Detail Pembelian
                </h2>

                <p class="text-muted">
                    Informasi lengkap pembelian
                </p>

            </div>
            <a href="{{ route('faktur.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </div>

        {{-- Informasi Pembelian --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                Informasi Pembelian
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <strong>Nomor Pembelian</strong>
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
                        <strong>Dibuat Oleh</strong>
                        <p>{{ $faktur->user->name ?? '-' }}</p>
                    </div>

                </div>

            </div>

        </div>

        {{-- Item Pembelian --}}
        <div class="card shadow-sm">

            <div class="card-header">

                Item Pembelian

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered">

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

                            @foreach ($faktur->detailFakturs as $detail)
                                <tr>

                                    <td>{{ $detail->product->kode_produk ?? '-' }}</td>
                                    <td>{{ $detail->product->nama_produk ?? 'Produk tidak ditemukan' }}</td>

                                    <td>

                                        {{ $detail->qty }} {{ $detail->product->satuan ?? '' }}

                                    </td>

                                    <td>

                                        {{ $detail->qty }} x Rp{{ number_format($detail->harga, 0, ',', '.') }}

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
