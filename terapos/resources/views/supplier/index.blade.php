@extends('app.master')

@section('content')
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
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

        .btn-add-supplier {
            background: linear-gradient(135deg,
                    #2563eb,
                    #3b82f6);
            border: none;
            color: #fff;
            padding: 12px 22px;
            border-radius: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: .3s;
        }

        .btn-add-supplier:hover {
            color: #fff;
            transform: translateY(-2px);
            box-shadow:
                0 10px 25px rgba(37, 99, 235, .25);
        }

        .supplier-stat {
            background: #fff;
            border-radius: 18px;
            padding: 22px;
            box-shadow:
                0 10px 30px rgba(15, 23, 42, .06);
            border-top: 4px solid #2563eb;
        }

        .supplier-stat h3 {
            margin: 0;
            font-weight: 800;
            color: #0f172a;
        }

        .supplier-stat p {
            margin: 6px 0 0;
            color: #64748b;
        }

        .search-card,
        .data-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow:
                0 10px 30px rgba(15, 23, 42, .06);
        }

        .search-box {
            position: relative;
        }

        .search-box i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .search-input {
            height: 52px;
            padding-left: 46px;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
        }

        .search-input:focus {
            border-color: #2563eb;
            box-shadow: none;
        }

        .btn-search {
            height: 52px;
            border-radius: 14px;
            font-weight: 600;
        }

        .supplier-table thead th {
            background: #f8fafc;
            color: #475569;
            border: none;
            padding: 18px;
            font-weight: 700;
        }

        .supplier-table tbody td {
            padding: 18px;
            vertical-align: middle;
            border-color: #f1f5f9;
        }

        .supplier-table tbody tr:hover {
            background: #f8fbff;
        }

        .supplier-code {
            color: #2563eb;
            font-weight: 700;
        }

        .supplier-name {
            font-weight: 600;
            color: #0f172a;
        }

        .action-btn {
            width: 38px;
            height: 38px;
            border: none;
            border-radius: 10px;
            transition: .25s;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .btn-view {
            background: rgba(37, 99, 235, .12);
            color: #2563eb;
        }

        .btn-view:hover {
            background: rgba(37, 99, 235, .2);
            color: #2563eb;
        }

        .btn-edit {
            background: rgba(245, 158, 11, .12);
            color: #d97706;
        }

        .btn-edit:hover {
            background: rgba(245, 158, 11, .2);
            color: #d97706;
        }

        .btn-delete {
            background: rgba(239, 68, 68, .12);
            color: #dc2626;
        }

        .btn-delete:hover {
            background: rgba(239, 68, 68, .2);
            color: #dc2626;
        }

        .empty-state {
            padding: 60px 0;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 3rem;
            display: block;
            margin-bottom: 12px;
        }
    </style>

    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="page-header">

            <div>

                <h2 class="page-title">
                    Data Supplier
                </h2>

                <p class="page-subtitle">
                    Kelola seluruh supplier Richie Motor
                </p>

            </div>

            <a href="{{ route('supplier.create') }}" class="btn-add-supplier">

                <i class="bi bi-plus-circle-fill me-2"></i>
                Tambah Supplier

            </a>

        </div>

        {{-- STATISTIK --}}
        <div class="row mb-4">

            <div class="col-md-3">

                <div class="supplier-stat">

                    <h3>
                        {{ $suppliers->total() }}
                    </h3>

                    <p>
                        Total Supplier
                    </p>

                </div>

            </div>

        </div>

        {{-- ALERT --}}
        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm">

                <i class="bi bi-check-circle-fill me-2"></i>

                {{ session('success') }}

            </div>
        @endif

        {{-- SEARCH --}}
        <div class="card search-card mb-4">

            <div class="card-body p-4">

                <form method="GET" action="{{ route('supplier.index') }}">

                    <div class="row g-3">

                        <div class="col-md-10">

                            <div class="search-box">

                                <i class="bi bi-search"></i>

                                <input type="text" name="search" class="form-control search-input"
                                    placeholder="Cari kode atau nama supplier..." value="{{ request('search') }}">

                            </div>

                        </div>

                        <div class="col-md-2">

                            <button type="submit" class="btn btn-primary btn-search w-100">

                                Cari

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

        {{-- TABLE --}}
        <div class="card data-card">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table supplier-table mb-0">

                        <thead>

                            <tr>

                                <th>Kode Supplier</th>
                                <th>Nama Supplier</th>
                                <th>Kontak</th>
                                <th>Telepon</th>
                                <th>Email</th>

                                <th width="140">
                                    Aksi
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($suppliers as $supplier)
                                <tr>

                                    <td>

                                        <span class="supplier-code">

                                            {{ $supplier->kode_supplier }}

                                        </span>

                                    </td>

                                    <td>

                                        <span class="supplier-name">

                                            {{ $supplier->nama_supplier }}

                                        </span>

                                    </td>

                                    <td>

                                        {{ $supplier->nama_kontak }}

                                    </td>

                                    <td>

                                        {{ $supplier->telepon }}

                                    </td>

                                    <td>

                                        {{ $supplier->email }}

                                    </td>

                                    <td>

                                        <div class="d-flex gap-2">

                                            <a href="{{ route('supplier.show', $supplier->id) }}"
                                                class="btn action-btn btn-view" title="Detail">

                                                <i class="bi bi-eye-fill"></i>

                                            </a>

                                            <a href="{{ route('supplier.edit', $supplier->id) }}"
                                                class="btn action-btn btn-edit" title="Edit">

                                                <i class="bi bi-pencil-fill"></i>

                                            </a>

                                            <form action="{{ route('supplier.destroy', $supplier->id) }}" method="POST">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn action-btn btn-delete" title="Hapus"
                                                    onclick="return confirm('Yakin ingin menghapus supplier ini?')">

                                                    <i class="bi bi-trash-fill"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6">

                                        <div class="empty-state text-center">

                                            <i class="bi bi-truck"></i>

                                            Tidak ada data supplier

                                        </div>

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        {{-- PAGINATION --}}
        <div class="mt-4">

            {{ $suppliers->links() }}

        </div>

    </div>
@endsection
