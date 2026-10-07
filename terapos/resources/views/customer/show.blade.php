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

        .detail-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .06);
            margin-bottom: 24px;
        }

        .detail-card .card-header {
            padding: 18px 24px;
            font-weight: 700;
            font-size: 1rem;
            border: none;
        }

        .detail-card .card-body {
            padding: 28px;
        }

        .info-label {
            font-size: .8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #94a3b8;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 1rem;
            font-weight: 600;
            color: #0f172a;
            margin: 0;
        }

        .info-group {
            margin-bottom: 24px;
        }

        .customer-code-badge {
            background: rgba(37, 99, 235, .1);
            color: #2563eb;
            padding: 6px 14px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1rem;
            display: inline-block;
        }

        .status-active {
            background: rgba(16, 185, 129, .12);
            color: #059669;
            padding: 7px 16px;
            border-radius: 999px;
            font-size: .85rem;
            font-weight: 600;
            display: inline-block;
        }

        .status-inactive {
            background: rgba(239, 68, 68, .12);
            color: #dc2626;
            padding: 7px 16px;
            border-radius: 999px;
            font-size: .85rem;
            font-weight: 600;
            display: inline-block;
        }

        .btn-back {
            background: #f1f5f9;
            border: none;
            color: #475569;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: .25s;
        }

        .btn-back:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .btn-edit-detail {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: .25s;
        }

        .btn-edit-detail:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(245, 158, 11, .3);
        }

        .divider {
            border-color: #f1f5f9;
            margin: 8px 0 24px;
        }

        .info-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
    </style>

    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="page-header">
            <div>
                <h2 class="page-title">Detail Pelanggan</h2>
                <p class="page-subtitle">Informasi lengkap data pelanggan</p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('customer.index') }}" class="btn-back">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
                <a href="{{ route('customer.edit', $customer->id) }}" class="btn-edit-detail">
                    <i class="bi bi-pencil-fill me-1"></i> Edit Pelanggan
                </a>
            </div>
        </div>

        {{-- INFORMASI UTAMA --}}
        <div class="card detail-card">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-person-fill me-2"></i>
                Informasi Pelanggan
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-md-6 info-group">
                        <p class="info-label">Kode Pelanggan</p>
                        <span class="customer-code-badge">{{ $customer->kode_customer }}</span>
                    </div>

                    <div class="col-md-6 info-group">
                        <p class="info-label">Status</p>
                        @if ($customer->aktif)
                            <span class="status-active">
                                <i class="bi bi-check-circle-fill me-1"></i> Aktif
                            </span>
                        @else
                            <span class="status-inactive">
                                <i class="bi bi-x-circle-fill me-1"></i> Nonaktif
                            </span>
                        @endif
                    </div>

                    <div class="col-md-12 info-group mb-0">
                        <p class="info-label">Nama Pelanggan</p>
                        <p class="info-value" style="font-size: 1.15rem;">
                            {{ $customer->nama_customer }}
                        </p>
                    </div>

                </div>
            </div>
        </div>

        {{-- KONTAK --}}
        <div class="card detail-card">
            <div class="card-header text-white" style="background: linear-gradient(135deg, #0891b2, #38bdf8);">
                <i class="bi bi-telephone-fill me-2"></i>
                Informasi Kontak
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-md-6 info-group">
                        <p class="info-label">Telepon</p>
                        <p class="info-value">
                            <i class="bi bi-telephone-fill me-1 text-success"></i>
                            {{ $customer->telepon ?? '-' }}
                        </p>
                    </div>

                    <div class="col-md-6 info-group">
                        <p class="info-label">Email</p>
                        <p class="info-value">
                            <i class="bi bi-envelope-fill me-1 text-primary"></i>
                            {{ $customer->email ?? '-' }}
                        </p>
                    </div>

                    <div class="col-md-12 info-group mb-0">
                        <p class="info-label">Alamat</p>
                        <p class="info-value" style="font-weight: 400; color: #475569; line-height: 1.6;">
                            <i class="bi bi-geo-alt-fill me-1 text-danger"></i>
                            {{ $customer->alamat ?? 'Alamat belum diisi.' }}
                        </p>
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection
