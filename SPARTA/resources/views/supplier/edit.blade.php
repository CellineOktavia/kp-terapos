@extends('app.master')

@section('content')
    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="fw-bold">
                    Edit Supplier
                </h2>

                <p class="text-muted mb-0">
                    Perbarui informasi supplier Richie Motor
                </p>

            </div>

        </div>

        {{-- Error Validation --}}
        @if ($errors->any())
            <div class="alert alert-danger">

                <ul class="mb-0">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>
        @endif

        {{-- Form Card --}}
        <div class="card border-0 shadow-sm">

            <div class="card-body p-4">

                <form action="{{ route('supplier.update', $supplier->id) }}" method="POST">

                    @csrf
                    @method('PUT')

                    <div class="row">

                        {{-- Kode Supplier --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-semibold">

                                Kode Supplier

                            </label>

                            <input type="text" name="kode_supplier" class="form-control"
                                value="{{ old('kode_supplier', $supplier->kode_supplier) }}"
                                placeholder="Masukkan kode supplier">

                        </div>

                        {{-- Nama Supplier --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-semibold">

                                Nama Supplier

                            </label>

                            <input type="text" name="nama_supplier" class="form-control"
                                value="{{ old('nama_supplier', $supplier->nama_supplier) }}"
                                placeholder="Masukkan nama supplier">

                        </div>

                        {{-- Nama Kontak --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-semibold">

                                Nama Kontak

                            </label>

                            <input type="text" name="nama_kontak" class="form-control"
                                value="{{ old('nama_kontak', $supplier->nama_kontak) }}" placeholder="Masukkan nama kontak">

                        </div>

                        {{-- Telepon --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-semibold">

                                Telepon

                            </label>

                            <input type="text" name="telepon" class="form-control"
                                value="{{ old('telepon', $supplier->telepon) }}" placeholder="Masukkan nomor telepon">

                        </div>

                        {{-- Email --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-semibold">

                                Email

                            </label>

                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $supplier->email) }}" placeholder="Masukkan email">

                        </div>

                        {{-- Status --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-semibold">

                                Status Supplier

                            </label>

                            <select name="aktif" class="form-select">

                                <option value="1" {{ old('aktif', $supplier->aktif) == 1 ? 'selected' : '' }}>
                                    Aktif
                                </option>

                                <option value="0" {{ old('aktif', $supplier->aktif) == 0 ? 'selected' : '' }}>
                                    Nonaktif
                                </option>

                            </select>

                        </div>

                        {{-- Alamat --}}
                        <div class="col-12 mb-4">

                            <label class="form-label fw-semibold">

                                Alamat

                            </label>

                            <textarea name="alamat" class="form-control" rows="4" placeholder="Masukkan alamat supplier">{{ old('alamat', $supplier->alamat) }}</textarea>

                        </div>

                    </div>

                    {{-- Action Button --}}
                    <div class="d-flex gap-2">

                        <button type="submit" class="btn btn-primary">

                            Simpan Perubahan

                        </button>

                        <a href="{{ route('supplier.index') }}" class="btn btn-light border">

                            Batal

                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>
@endsection
