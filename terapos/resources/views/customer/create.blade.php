@extends('app.master')

@section('content')
    <div class="container-fluid">

        <h2 class="fw-bold mb-4">

            Tambah Pelanggan

        </h2>

        <div class="card shadow-sm">

            <div class="card-body">

                <form action="{{ route('customer.store') }}" method="POST">

                    @csrf

                    <div class="mb-3">

                        <label class="form-label fw-semibold">

                            Kode Customer

                        </label>

                        <input type="text" class="form-control" value="Otomatis dibuat oleh sistem" readonly>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Nama Pelanggan

                        </label>

                        <input type="text" name="nama_customer" class="form-control" required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Telepon

                        </label>

                        <input type="text" name="telepon" class="form-control">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Email

                        </label>

                        <input type="email" name="email" class="form-control">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Alamat

                        </label>

                        <textarea name="alamat" rows="3" class="form-control"></textarea>

                    </div>

                    <button class="btn btn-primary">

                        Simpan

                    </button>

                    <a href="{{ route('customer.index') }}" class="btn btn-secondary">

                        Kembali

                    </a>

                </form>

            </div>

        </div>

    </div>
@endsection
