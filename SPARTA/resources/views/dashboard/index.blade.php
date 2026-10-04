@extends('app.master')

@section('content')
    <div class="container-fluid">

        <h2 class="fw-bold mb-4">

            Dashboard SPARTA

        </h2>

        {{-- FILTER --}}
        <div class="card shadow-sm mb-4">

            <div class="card-body">

                <form method="GET">

                    <div class="row">

                        <div class="col-md-4">

                            <label>

                                Tanggal Awal

                            </label>

                            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">

                        </div>

                        <div class="col-md-4">

                            <label>

                                Tanggal Akhir

                            </label>

                            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">

                        </div>

                        <div class="col-md-4">

                            <label>

                                &nbsp;

                            </label>

                            <button class="btn btn-primary w-100">

                                Filter

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

        {{-- CARD STATISTIK --}}
        <div class="row mb-4">

            <div class="col-md-3">

                <div class="card shadow-sm">

                    <div class="card-body">

                        <h6>Total Produk</h6>

                        <h2>

                            {{ $totalProduk }}

                        </h2>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card shadow-sm">

                    <div class="card-body">

                        <h6>Total Supplier</h6>

                        <h2>

                            {{ $totalSupplier }}

                        </h2>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card shadow-sm">

                    <div class="card-body">

                        <h6>Total Pelanggan</h6>

                        <h2>

                            {{ $totalCustomer }}

                        </h2>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card shadow-sm">

                    <div class="card-body">

                        <h6>Stok Kritis</h6>

                        <h2 class="text-danger">

                            {{ $stokKritis }}

                        </h2>

                    </div>

                </div>

            </div>

        </div>

        {{-- OMZET --}}
        <div class="card shadow-sm mb-4">

            <div class="card-body">

                <h5>

                    Total Pendapatan

                </h5>

                <h2 class="text-success">

                    Rp
                    {{ number_format($totalPenjualan, 0, ',', '.') }}

                </h2>

            </div>

        </div>

        {{-- GRAFIK --}}
        <div class="card shadow-sm">

            <div class="card-body">

                <h5 class="mb-4">

                    Grafik Pendapatan Harian

                </h5>

                <canvas id="incomeChart">

                </canvas>

            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const labels =
            {!! json_encode($pendapatanHarian->pluck('tanggal')) !!};

        const data =
            {!! json_encode($pendapatanHarian->pluck('total')) !!};

        new Chart(

            document.getElementById(
                'incomeChart'
            ),

            {

                type: 'bar',

                data: {

                    labels: labels,

                    datasets: [

                        {

                            label: 'Pendapatan Harian',

                            data: data,

                            borderWidth: 1

                        }

                    ]

                },

                options: {

                    responsive: true,

                    maintainAspectRatio: false

                }

            }

        );
    </script>
@endpush
