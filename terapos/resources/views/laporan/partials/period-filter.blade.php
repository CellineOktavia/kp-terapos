<style>
    .filter-card-terapos {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 18px 22px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
    }

    .filter-section-label {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
        display: block;
    }

    .preset-pill-group {
        display: inline-flex;
        flex-wrap: wrap;
        gap: 6px;
        background-color: #f8fafc;
        padding: 4px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }

    .btn-preset-terapos {
        border: none;
        background: transparent;
        color: #475569;
        font-size: 13px;
        font-weight: 600;
        padding: 7px 14px;
        border-radius: 9px;
        text-decoration: none;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .btn-preset-terapos:hover {
        color: #15803d;
        background-color: #f0fdf4;
    }

    .btn-preset-terapos.active {
        background-color: #198754;
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(25, 135, 84, 0.25);
    }

    .input-terapos-date {
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 0.875rem;
        font-weight: 500;
        color: #1e293b;
        height: 40px;
        transition: all 0.2s ease;
    }

    .input-terapos-date:focus {
        border-color: #198754;
        box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.14);
        outline: none;
    }

    .btn-terapos-apply {
        background-color: #198754;
        border: 1px solid #198754;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.875rem;
        height: 40px;
        padding: 0 18px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    .btn-terapos-apply:hover {
        background-color: #157347;
        border-color: #146c43;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(25, 135, 84, 0.2);
    }
</style>

<div class="filter-card-terapos mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-xl-5 col-lg-12">
            <span class="filter-section-label">
                <i class="bi bi-calendar-range text-success me-1"></i>Pilih Cepat Periode
            </span>
            <div class="preset-pill-group">
                @foreach (['today' => 'Hari Ini', 'week' => 'Minggu Ini', 'month' => 'Bulan Ini'] as $key => $label)
                    <a href="{{ route($reportRoute, ['period' => $key]) }}"
                        class="btn-preset-terapos {{ $period['key'] === $key ? 'active' : '' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="col-xl-7 col-lg-12">
            <form method="GET" action="{{ route($reportRoute) }}" class="row g-2 align-items-end">
                <input type="hidden" name="period" value="custom">
                <div class="col-sm-4">
                    <label for="start_date" class="filter-section-label">Tanggal Mulai</label>
                    <input id="start_date" name="start_date" type="date" class="form-control input-terapos-date"
                        value="{{ $period['key'] === 'custom' ? $period['start']->toDateString() : '' }}" required>
                </div>
                <div class="col-sm-4">
                    <label for="end_date" class="filter-section-label">Tanggal Akhir</label>
                    <input id="end_date" name="end_date" type="date" class="form-control input-terapos-date"
                        value="{{ $period['key'] === 'custom' ? $period['end']->toDateString() : '' }}" required>
                </div>
                <div class="col-sm-4">
                    <button type="submit" class="btn-terapos-apply w-100">
                        <i class="bi bi-funnel-fill"></i>
                        <span>Terapkan Filter</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
