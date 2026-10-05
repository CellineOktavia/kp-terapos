<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2 mb-3">
            @foreach (['today' => 'Hari Ini', 'week' => 'Minggu Ini', 'month' => 'Bulan Ini'] as $key => $label)
                <a href="{{ route($reportRoute, ['period' => $key]) }}"
                    class="btn {{ $period['key'] === $key ? 'btn-primary' : 'btn-outline-primary' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <form method="GET" action="{{ route($reportRoute) }}" class="row g-3 align-items-end">
            <input type="hidden" name="period" value="custom">
            <div class="col-sm-5">
                <label for="start_date" class="form-label">Tanggal Mulai</label>
                <input id="start_date" name="start_date" type="date" class="form-control"
                    value="{{ $period['key'] === 'custom' ? $period['start']->toDateString() : '' }}" required>
            </div>
            <div class="col-sm-5">
                <label for="end_date" class="form-label">Tanggal Akhir</label>
                <input id="end_date" name="end_date" type="date" class="form-control"
                    value="{{ $period['key'] === 'custom' ? $period['end']->toDateString() : '' }}" required>
            </div>
            <div class="col-sm-2">
                <button type="submit" class="btn btn-outline-primary w-100">Terapkan</button>
            </div>
        </form>
    </div>
</div>
