@extends('admin.layout')
@section('title', $code->exists ? 'Redaguoti promo kodą' : 'Naujas promo kodas')

@section('admin')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.promo-codes.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Atgal
    </a>
    <h1 class="fw-bold m-0 fs-4">
        {{ $code->exists ? 'Redaguoti: ' . $code->code : 'Naujas promo kodas' }}
    </h1>
</div>

<div class="admin-form-shell">
    <form method="POST"
          action="{{ $code->exists ? route('admin.promo-codes.update', $code) : route('admin.promo-codes.store') }}">
        @csrf
        @if($code->exists) @method('PUT') @endif

        <div class="admin-form-card mb-3">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Pagrindinis</h6>
                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label small">Kodas <span class="text-danger">*</span></label>
                        <input type="text" name="code"
                               value="{{ old('code', $code->code) }}"
                               class="form-control font-monospace text-uppercase @error('code') is-invalid @enderror"
                               placeholder="pvz. VASARA20" maxlength="50" required
                               style="letter-spacing:.08em">
                        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">Automatiškai verčiama į didžiąsias raides.</div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small">Nuolaidos tipas <span class="text-danger">*</span></label>
                        <select name="type" id="promoType" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="percent" @selected(old('type', $code->type) === 'percent')>Procentinė (%)</option>
                            <option value="fixed"   @selected(old('type', $code->type) === 'fixed')>Fiksuota suma (€)</option>
                        </select>
                        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small" id="valueLabel">
                            Nuolaidos dydis <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" name="value" id="promoValue" step="0.01" min="0.01"
                                   value="{{ old('value', $code->value) }}"
                                   class="form-control @error('value') is-invalid @enderror" required>
                            <span class="input-group-text" id="valueSuffix">%</span>
                        </div>
                        @error('value')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1"
                                   @checked(old('is_active', $code->is_active ?? true))>
                            <label class="form-check-label small fw-semibold" for="isActive">Aktyvus</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-form-card mb-3">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Apribojimai</h6>
                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label small">Min. užsakymo suma (€)</label>
                        <input type="number" name="min_order" step="0.01" min="0"
                               value="{{ old('min_order', $code->min_order) }}"
                               class="form-control @error('min_order') is-invalid @enderror"
                               placeholder="pvz. 30.00">
                        @error('min_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">Palik tuščią — kodas veiks be minimalios sumos.</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small">Naudojimo limitas (viso)</label>
                        <input type="number" name="usage_limit" min="1"
                               value="{{ old('usage_limit', $code->usage_limit) }}"
                               class="form-control @error('usage_limit') is-invalid @enderror"
                               placeholder="pvz. 100">
                        @error('usage_limit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">Palik tuščią — neribotas naudojimas.</div>
                    </div>

                </div>
            </div>
        </div>

        <div class="admin-form-card mb-4">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Galiojimo laikas</h6>
                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label small">Galioja nuo</label>
                        <input type="datetime-local" name="starts_at"
                               value="{{ old('starts_at', $code->starts_at?->format('Y-m-d\TH:i')) }}"
                               class="form-control @error('starts_at') is-invalid @enderror">
                        @error('starts_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small">Galioja iki</label>
                        <input type="datetime-local" name="expires_at"
                               value="{{ old('expires_at', $code->expires_at?->format('Y-m-d\TH:i')) }}"
                               class="form-control @error('expires_at') is-invalid @enderror">
                        @error('expires_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-text pb-1">
                            Palik abu laukus tuščius — kodas galioja neribotą laiką.
                        </div>
                    </div>

                </div>
            </div>
            <div class="card-footer d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>
                    {{ $code->exists ? 'Išsaugoti pakeitimus' : 'Sukurti kodą' }}
                </button>
                <a href="{{ route('admin.promo-codes.index') }}" class="btn btn-outline-secondary">Atšaukti</a>
            </div>
        </div>

    </form>
</div>

@push('scripts')
<script>
(function () {
    const typeSelect  = document.getElementById('promoType');
    const valueSuffix = document.getElementById('valueSuffix');
    const valueInput  = document.getElementById('promoValue');

    function syncSuffix() {
        const isPercent = typeSelect.value === 'percent';
        valueSuffix.textContent = isPercent ? '%' : '€';
        if (isPercent) valueInput.setAttribute('max', '100');
        else           valueInput.removeAttribute('max');
    }

    typeSelect.addEventListener('change', syncSuffix);
    syncSuffix();
})();
</script>
@endpush
@endsection
