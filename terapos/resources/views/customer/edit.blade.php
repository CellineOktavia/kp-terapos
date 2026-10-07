@extends('app.master')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
@endpush

@section('content')
    <div class="container-fluid">

        <h2 class="fw-bold mb-4">

            Edit Pelanggan

        </h2>

        <div class="card shadow-sm">

            <div class="card-body">

                <form action="{{ route('customer.update', $customer) }}" method="POST">

                    @csrf
                    @method('PUT')

                    <div class="mb-3">

                        <label class="form-label">

                            Kode Pelanggan

                        </label>

                        <input type="text" name="kode_customer" class="form-control"
                            value="{{ $customer->kode_customer }}" required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Nama Pelanggan

                        </label>

                        <input type="text" name="nama_customer" class="form-control"
                            value="{{ $customer->nama_customer }}" required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Telepon

                        </label>

                        <input type="text" name="telepon" class="form-control" value="{{ $customer->telepon }}">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Email

                        </label>

                        <input type="email" name="email" class="form-control" value="{{ $customer->email }}">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Alamat

                        </label>

                        <textarea name="alamat" rows="3" class="form-control">{{ $customer->alamat }}</textarea>

                    </div>

                    <button class="btn btn-primary">

                        Update

                    </button>

                    <a href="{{ route('customer.index') }}" class="btn btn-secondary">

                        Kembali

                    </a>

                </form>

            </div>

        </div>

    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                placeholder: 'Cari data...',
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endpush
