@extends('admin.layout')
@section('title', 'Promo kodai')

@section('admin')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="fw-bold m-0">Promo kodai</h1>
    <a href="{{ route('admin.promo-codes.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Naujas kodas
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3">
        {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-3">
        {{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="admin-table-wrap rounded">
    <table class="admin-table table m-0">
        <thead>
            <tr>
                <th>Kodas</th>
                <th>Tipas</th>
                <th>Vertė</th>
                <th>Min. suma</th>
                <th>Naudota / Limitas</th>
                <th>Galioja nuo</th>
                <th>Galioja iki</th>
                <th>Aktyvus</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @forelse($codes as $c)
            <tr>
                <td><strong class="font-monospace">{{ $c->code }}</strong></td>
                <td>
                    <span class="badge {{ $c->type === 'percent' ? 'bg-primary' : 'bg-info text-dark' }}">
                        {{ $c->type === 'percent' ? '%' : '€ fiksuota' }}
                    </span>
                </td>
                <td>{{ $c->type === 'percent' ? $c->value . '%' : '€' . number_format($c->value, 2) }}</td>
                <td>{{ $c->min_order ? '€' . number_format($c->min_order, 2) : '—' }}</td>
                <td>
                    {{ $c->used_count }}
                    @if($c->usage_limit) / {{ $c->usage_limit }} @else / ∞ @endif
                    @if($c->usage_limit && $c->used_count >= $c->usage_limit)
                        <span class="badge bg-danger ms-1">Išnaudotas</span>
                    @endif
                </td>
                <td class="text-muted small">{{ $c->starts_at?->format('Y-m-d') ?? '—' }}</td>
                <td class="text-muted small">
                    {{ $c->expires_at?->format('Y-m-d') ?? '—' }}
                    @if($c->expires_at && now()->gt($c->expires_at))
                        <span class="badge bg-secondary ms-1">Pasibaigė</span>
                    @endif
                </td>
                <td>
                    @if($c->is_active)
                        <span class="badge bg-success">Aktyvus</span>
                    @else
                        <span class="badge bg-secondary">Neaktyvus</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.promo-codes.edit', $c) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.promo-codes.destroy', $c) }}"
                              onsubmit="return confirm('Ištrinti kodą {{ $c->code }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash3"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center text-muted py-4">Dar nėra promo kodų.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
