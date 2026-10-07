<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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

        .btn-add-customer {
            background: linear-gradient(135deg,
                    #2563eb,
                    #3b82f6);
            border: none;
            color: white;
            padding: 12px 22px;
            border-radius: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: .3s;
        }

        .btn-add-customer:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow:
                0 10px 25px rgba(37, 99, 235, .25);
        }

        .customer-stat {
            background: white;
            border-radius: 18px;
            padding: 22px;
            box-shadow:
                0 10px 30px rgba(15, 23, 42, .06);

            border-top: 4px solid #2563eb;
        }

        .customer-stat h3 {
            margin: 0;
            font-weight: 800;
            color: #0f172a;
        }

        .customer-stat p {
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
            padding-left: 45px;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
        }

        .search-input:focus {
            box-shadow: none;
            border-color: #2563eb;
        }

        .btn-search {
            height: 52px;
            border-radius: 14px;
            font-weight: 600;
        }

        .customer-table thead th {
            background: #f8fafc;
            color: #475569;
            border: none;
            padding: 18px;
            font-weight: 700;
        }

        .customer-table tbody td {
            padding: 18px;
            vertical-align: middle;
            border-color: #f1f5f9;
        }

        .customer-table tbody tr:hover {
            background: #f8fbff;
        }

        .customer-code {
            color: #2563eb;
            font-weight: 700;
        }

        .customer-name {
            font-weight: 600;
            color: #0f172a;
        }

        .status-active {
            background: rgba(16, 185, 129, .12);
            color: #059669;
            padding: 7px 14px;
            border-radius: 999px;
            font-size: .85rem;
            font-weight: 600;
        }

        .status-inactive {
            background: rgba(239, 68, 68, .12);
            color: #dc2626;
            padding: 7px 14px;
            border-radius: 999px;
            font-size: .85rem;
            font-weight: 600;
        }

        .action-btn {
            width: 38px;
            height: 38px;
            border: none;
            border-radius: 10px;
            transition: .25s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .btn-view {
            background: rgba(14, 165, 233, .12);
            color: #0284c7;
        }

        .btn-view:hover {
            background: rgba(14, 165, 233, .2);
            color: #0284c7;
        }

        .btn-edit {
            background: rgba(245, 158, 11, .12);
            color: #d97706;
        }

        .btn-delete {
            background: rgba(239, 68, 68, .12);
            color: #dc2626;
        }

        .btn-edit:hover {
            background: rgba(245, 158, 11, .2);
            color: #d97706;
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
            margin-bottom: 10px;
            display: block;
        }
    </style>

    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="page-header">

            <div>

                <h2 class="page-title">
                    Data Pelanggan
                </h2>

                <p class="page-subtitle">
                    Kelola seluruh pelanggan Richie Motor
                </p>

            </div>

            <a href="{{ route('customer.create') }}" class="btn-add-customer">

                <i class="bi bi-person-plus-fill me-2"></i>
                Tambah Pelanggan

            </a>

        </div>

        {{-- INFO CARD --}}
        <div class="row mb-4">

            <div class="col-md-3">

                <div class="customer-stat">

                    <h3>
                        {{ $customers->total() }}
                    </h3>

                    <p>
                        Total Pelanggan
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

                <form method="GET" action="{{ route('customer.index') }}">

                    <div class="row g-3">

                        <div class="col-md-10">

                            <div class="search-box">

                                <i class="bi bi-search"></i>

                                <input type="text" name="search" class="form-control search-input"
                                    placeholder="Cari kode atau nama pelanggan..." value="{{ request('search') }}">

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

                    <table class="table customer-table mb-0">

                        <thead>

                            <tr>

                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Telepon</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th width="120">
                                    Aksi
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($customers as $customer)
                                <tr>

                                    <td>

                                        <span class="customer-code">

                                            {{ $customer->kode_customer }}

                                        </span>

                                    </td>

                                    <td>

                                        <span class="customer-name">

                                            {{ $customer->nama_customer }}

                                        </span>

                                    </td>

                                    <td>

                                        {{ $customer->telepon }}

                                    </td>

                                    <td>

                                        {{ $customer->email }}

                                    </td>

                                    <td>

                                        @if ($customer->aktif)
                                            <span class="status-active">

                                                Aktif

                                            </span>
                                        @else
                                            <span class="status-inactive">

                                                Nonaktif

                                            </span>
                                        @endif

                                    </td>

                                    <td>

                                        <div class="d-flex gap-2">

                                            <a href="{{ route('customer.show', $customer->id) }}"
                                                class="btn action-btn btn-view" title="Detail">

                                                <i class="bi bi-eye-fill"></i>

                                            </a>

                                            <a href="{{ route('customer.edit', $customer) }}"
                                                class="btn action-btn btn-edit">

                                                <i class="bi bi-pencil-fill"></i>

                                            </a>

                                            <form action="{{ route('customer.destroy', $customer) }}" method="POST">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn action-btn btn-delete"
                                                    onclick="return confirm('Hapus pelanggan ini?')">

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

                                            <i class="bi bi-people"></i>

                                            Tidak ada data pelanggan

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

            {{ $customers->links() }}

        </div>

    </div>
@endsection
