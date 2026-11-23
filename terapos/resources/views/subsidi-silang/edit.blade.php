@extends('app.master')

@section('content')
    <div class="container-fluid px-0">
        <div class="mb-4">
            <h3 class="fw-bold text-dark mb-1">Subsidi Silang</h3>
            <p class="text-muted small mb-0">Kelola alokasi margin dan penggunaan dana subsidi antarproduk.</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 rounded-3">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-3">
                <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card card-terapos h-100"><div class="card-body">
                    <span class="label-title">Dana Terkumpul</span>
                    <div class="fw-bold fs-4">Rp {{ number_format($collected, 0, ',', '.') }}</div>
                </div></div>
            </div>
            <div class="col-md-4">
                <div class="card card-terapos h-100"><div class="card-body">
                    <span class="label-title">Subsidi Digunakan</span>
                    <div class="fw-bold fs-4">Rp {{ number_format(max(0, $used - $returnedSubsidy), 0, ',', '.') }}</div>
                </div></div>
            </div>
            <div class="col-md-4">
                <div class="card card-terapos h-100"><div class="card-body">
                    <span class="label-title">Saldo Tersedia</span>
                    <div class="fw-bold fs-4 {{ $balance < 0 ? 'text-danger' : 'text-success' }}">
                        Rp {{ number_format($balance, 0, ',', '.') }}
                    </div>
                    @if ($balance < 0)
                        <small class="text-danger">Subsidi baru ditahan sampai saldo kembali positif.</small>
                    @endif
                </div></div>
            </div>
        </div>

        <div class="card card-terapos mb-4">
            <div class="card-header-green">Pengaturan Program</div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('subsidi-silang.update') }}" id="cross-subsidy-form">
                    @csrf
                    @method('PUT')
                    <div class="form-check form-switch mb-4">
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                            @checked((bool) old('is_active', $program->is_active))>
                        <label class="form-check-label fw-semibold" for="is_active">Program aktif</label>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="source_product_id" class="form-label">Produk pemberi subsidi</label>
                            <select class="form-select" name="source_product_id" id="source_product_id" required>
                                <option value="">Pilih produk pemberi</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" @selected(old('source_product_id', $program->source_product_id) == $product->id)>
                                        {{ $product->kode_produk }} - {{ $product->nama_produk }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="recipient_product_id" class="form-label">Produk penerima subsidi</label>
                            <select class="form-select" name="recipient_product_id" id="recipient_product_id" required>
                                <option value="">Pilih produk penerima</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" @selected(old('recipient_product_id', $program->recipient_product_id) == $product->id)>
                                        {{ $product->kode_produk }} - {{ $product->nama_produk }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="allocation_percentage" class="form-label">Alokasi margin produk pemberi (%)</label>
                            <input class="form-control" type="number" step="0.01" min="0" max="100"
                                name="allocation_percentage" id="allocation_percentage"
                                value="{{ old('allocation_percentage', $program->allocation_percentage) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="subsidy_amount" class="form-label">Nominal subsidi per unit (Rp)</label>
                            <input class="form-control" type="number" step="0.01" min="0"
                                name="subsidy_amount" id="subsidy_amount"
                                value="{{ old('subsidy_amount', $program->subsidy_amount) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="maximum_subsidy" class="form-label">Batas maksimal subsidi per transaksi (Rp)</label>
                            <input class="form-control" type="number" step="0.01" min="0"
                                name="maximum_subsidy" id="maximum_subsidy"
                                value="{{ old('maximum_subsidy', $program->maximum_subsidy) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="minimum_selling_price" class="form-label">Harga jual minimum penerima (Rp)</label>
                            <input class="form-control" type="number" step="0.01" min="0"
                                name="minimum_selling_price" id="minimum_selling_price"
                                value="{{ old('minimum_selling_price', $program->minimum_selling_price) }}" required>
                        </div>
                    </div>

                    <div class="alert alert-light border mt-4 mb-3">
                        <strong>Aturan:</strong> margin aktual setelah diskon transaksi dialokasikan menjadi dana.
                        Subsidi hanya dipakai jika saldo mencukupi dan harga akhir tetap di atas harga minimum.
                        Diskon penjualan diterapkan terlebih dahulu.
                    </div>
                    <button class="btn btn-success px-4" type="submit">Simpan Pengaturan</button>
                </form>

                <div class="small text-muted mt-3">
                    Terakhir diperbarui: {{ $program->updated_at?->format('d-m-Y H:i') ?? '-' }}
                    oleh {{ $program->updatedBy?->name ?? '-' }}.
                </div>
            </div>
        </div>

        <div class="card card-terapos">
            <div class="card-header-green">Riwayat Dana Subsidi</div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead><tr>
                        <th>Waktu</th><th>Jenis</th><th>Barang</th><th>Penjualan</th>
                        <th class="text-end">Nominal</th><th>Petugas</th>
                    </tr></thead>
                    <tbody>
                        @forelse ($entries as $entry)
                            <tr>
                                <td>{{ $entry->created_at->format('d-m-Y H:i') }}</td>
                                <td>{{ match ($entry->entry_type) {
                                    'contribution' => 'Dana masuk',
                                    'subsidy_use' => 'Subsidi digunakan',
                                    'source_return' => 'Pembalikan dana masuk',
                                    'subsidy_return' => 'Pengembalian subsidi',
                                    default => $entry->entry_type,
                                } }}</td>
                                <td>{{ $entry->product?->nama_produk ?? '-' }}</td>
                                <td>{{ $entry->penjualan?->nomor_penjualan ?? '-' }}</td>
                                <td class="text-end {{ $entry->amount < 0 ? 'text-danger' : 'text-success' }}">
                                    Rp {{ number_format($entry->amount, 0, ',', '.') }}
                                </td>
                                <td>{{ $entry->user?->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">Belum ada riwayat dana subsidi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($entries->hasPages())
                <div class="card-footer bg-white">{{ $entries->links() }}</div>
            @endif
        </div>
    </div>
@endsection
