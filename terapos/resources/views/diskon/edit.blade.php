@extends('app.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">Kelola Diskon Penjualan</h2>
                <p class="text-muted mb-0">Atur syarat minimum pembelian dan persentase diskon otomatis untuk transaksi penjualan.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 rounded-3 shadow-sm">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-3 shadow-sm">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-header bg-success text-white fw-semibold rounded-top-4">Pengaturan Diskon</div>
            <div class="card-body p-4">
                <form action="{{ route('diskon.update') }}" method="POST" id="discount-settings-form">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="is_active" class="form-label fw-semibold">Status Diskon</label>
                            <select class="form-select" id="is_active" name="is_active" required>
                                <option value="1" @selected((string) old('is_active', (int) $setting->is_active) === '1')>Aktif</option>
                                <option value="0" @selected((string) old('is_active', (int) $setting->is_active) === '0')>Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="condition_operator" class="form-label fw-semibold">Syarat Minimum Pembelian</label>
                            <select class="form-select" id="condition_operator" name="condition_operator" required>
                                <option value="gt" @selected(old('condition_operator', $setting->condition_operator) === 'gt')>Lebih dari (&gt;)</option>
                                <option value="gte" @selected(old('condition_operator', $setting->condition_operator) === 'gte')>Minimal atau sama dengan (&ge;)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="minimum_purchase" class="form-label fw-semibold">Minimum Pembelian (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input class="form-control" id="minimum_purchase" name="minimum_purchase" type="text"
                                    inputmode="numeric" value="{{ old('minimum_purchase', $setting->minimum_purchase) }}" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="percentage" class="form-label fw-semibold">Persentase Diskon (%)</label>
                            <div class="input-group">
                                <input class="form-control" id="percentage" name="percentage" type="number" step="0.01"
                                    min="0.01" max="100" value="{{ old('percentage', $setting->percentage) }}" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-light border mt-4 mb-0" aria-live="polite">
                        <div class="fw-semibold mb-2">Preview Aturan</div>
                        <div id="discount-rule-preview" class="small text-muted"></div>
                    </div>

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-4">
                        <div class="small text-muted">
                            <div>Terakhir diperbarui: {{ $setting->updated_at?->format('d/m/Y H:i') ?? '-' }}</div>
                            <div>Owner: {{ $setting->updatedBy?->name ?? 'Belum diperbarui' }}</div>
                        </div>
                        <button class="btn btn-success px-4" type="submit" id="save-discount-settings">
                            <i class="bi bi-check-lg me-1"></i> Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('discount-settings-form');
            const active = document.getElementById('is_active');
            const operator = document.getElementById('condition_operator');
            const minimum = document.getElementById('minimum_purchase');
            const percentage = document.getElementById('percentage');
            const preview = document.getElementById('discount-rule-preview');
            const original = {
                is_active: String(@json((int) $setting->is_active)),
                condition_operator: @json($setting->condition_operator),
                minimum_purchase: String(@json((int) $setting->minimum_purchase)),
                percentage: String(@json((float) $setting->percentage))
            };

            function rupiah(value) {
                return 'Rp' + Number(value).toLocaleString('id-ID', {
                    maximumFractionDigits: 2
                });
            }

            function eligible(subtotal, min, op, isActive) {
                return isActive && (op === 'gt' ? subtotal > min : subtotal >= min);
            }

            function updatePreview() {
                const min = Number(minimum.value.replace(/\D/g, '') || 0);
                const percent = Number(percentage.value || 0);
                const isActive = active.value === '1';
                const values = [min, min + 1, min + 100000];
                const lines = values.map(function(subtotal) {
                    if (!isActive) {
                        return 'Pembelian ' + rupiah(subtotal) + ' → Diskon nonaktif.';
                    }
                    if (!eligible(subtotal, min, operator.value, isActive)) {
                        return 'Pembelian ' + rupiah(subtotal) + ' → Tidak mendapat diskon.';
                    }
                    const discount = Math.round((subtotal * percent / 100 + Number.EPSILON) * 100) / 100;
                    return 'Pembelian ' + rupiah(subtotal) + ' → Diskon ' + rupiah(discount) +
                        ', total ' + rupiah(subtotal - discount) + '.';
                });
                preview.textContent = (isActive ? 'Diskon ' + percent + '% untuk pembelian ' +
                    (operator.value === 'gt' ? 'lebih dari ' : 'minimal ') + rupiah(min) + '.' :
                    'Diskon sedang nonaktif.') + ' ' + lines.join(' ');
            }

            minimum.addEventListener('input', updatePreview);
            [active, operator, percentage].forEach(input => input.addEventListener('input', updatePreview));
            minimum.addEventListener('blur', function() {
                const value = Number(minimum.value.replace(/\D/g, '') || 0);
                minimum.value = value.toLocaleString('id-ID');
            });
            form.addEventListener('submit', function(event) {
                const current = {
                    is_active: active.value,
                    condition_operator: operator.value,
                    minimum_purchase: minimum.value.replace(/\D/g, ''),
                    percentage: String(Number(percentage.value))
                };
                const changed = Object.keys(original).some(key => current[key] !== original[key]);
                if (changed && !window.confirm('Aturan diskon berubah. Simpan perubahan pengaturan ini?')) {
                    event.preventDefault();
                    return;
                }
                minimum.value = current.minimum_purchase;
                document.getElementById('save-discount-settings').disabled = true;
            });
            updatePreview();
        });
    </script>
@endpush
