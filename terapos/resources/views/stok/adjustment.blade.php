@extends('app.master')

@section('content')
    <div class="container-fluid">
        <div class="mb-4">
            <h2 class="fw-bold">Penyesuaian Stok</h2>
            <p class="text-muted">Catat koreksi stok fisik dengan alasan yang jelas.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('stok.adjustment.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Produk</label>
                        <select id="product_id" name="product_id" class="form-select" required>
                            <option value="">Pilih produk</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" data-stock="{{ $product->stok }}"
                                    data-unit="{{ $product->satuan }}" @selected(old('product_id') == $product->id)>
                                    {{ $product->kode_produk }} - {{ $product->nama_produk }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted" id="system-stock">Pilih produk untuk melihat stok sistem.</small>
                    </div>
                    <div class="mb-3">
                        <label for="jenis" class="form-label">Jenis Penyesuaian</label>
                        <select id="jenis" name="jenis" class="form-select" required>
                            <option value="">Pilih jenis</option>
                            <option value="masuk" @selected(old('jenis') === 'masuk')>Tambah</option>
                            <option value="keluar" @selected(old('jenis') === 'keluar')>Kurang</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="qty" class="form-label">Jumlah</label>
                        <input id="qty" type="number" name="qty" class="form-control"
                            value="{{ old('qty') }}" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="alasan" class="form-label">Alasan</label>
                        <input id="alasan" type="text" name="alasan" class="form-control"
                            value="{{ old('alasan') }}" maxlength="200" required
                            placeholder="Contoh: barang rusak atau koreksi stok awal">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Penyesuaian</button>
                    <a href="{{ route('stok.index') }}" class="btn btn-outline-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('product_id').addEventListener('change', function () {
            const option = this.selectedOptions[0];
            const text = option.value
                ? 'Stok sistem: ' + option.dataset.stock + ' ' + option.dataset.unit
                : 'Pilih produk untuk melihat stok sistem.';
            document.getElementById('system-stock').textContent = text;
        });
        document.getElementById('product_id').dispatchEvent(new Event('change'));
    </script>
@endpush
