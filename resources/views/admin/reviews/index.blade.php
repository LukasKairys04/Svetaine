@extends('admin.layout')
@section('title', 'Produktų atsiliepimai')

@section('admin')
    <form method="GET" class="card shadow-sm mb-3">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="small fw-semibold text-muted text-uppercase" style="letter-spacing:.05em">Filtrai</span>
                @if(request()->hasAny(['search','rating','approved']))
                    <a href="{{ route('admin.reviews.index') }}" class="small text-danger text-decoration-none"><i class="bi bi-x-circle me-1"></i>Valyti filtrus</a>
                @endif
            </div>
            <div class="row g-2 align-items-center">
                <div class="col-md-5"><input type="search" name="search" class="form-control form-control-sm" placeholder="Ieškoti pagal vartotoją, produktą, tekstą..." value="{{ request('search') }}"></div>
                <div class="col-md-2">
                    <select name="rating" class="form-select form-select-sm">
                        <option value="">Visos žvaigždutės</option>
                        @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" @selected(request('rating') == $i)>{{ $i }} ⭐</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="approved" class="form-select form-select-sm">
                        <option value="">Visi statusai</option>
                        <option value="1" @selected(request('approved') === '1')>Patvirtinti</option>
                        <option value="0" @selected(request('approved') === '0')>Nepatvirtinti</option>
                    </select>
                </div>
                <div class="col-md-auto">
                    <button class="btn btn-primary btn-sm">Ieškoti</button>
                </div>
            </div>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle m-0">
                <thead class="table-light">
                    <tr>
                        <th>Data</th>
                        <th>Vartotojas</th>
                        <th>Produktas</th>
                        <th>Įvertinimas</th>
                        <th>Komentaras</th>
                        <th>Statusas</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($reviews as $r)
                    <tr>
                        <td class="small text-muted">{{ $r->created_at->format('Y-m-d') }}</td>
                        <td><strong>{{ $r->user->name ?? 'Nežinomas' }}</strong></td>
                        <td>
                            <a href="{{ route('product.show', $r->product->slug ?? '#') }}" class="text-decoration-none small" target="_blank">
                                {{ Str::limit($r->product->name ?? '—', 40) }}
                            </a>
                        </td>
                        <td>{{ number_format($r->rating, 1) }} ⭐</td>
                        <td class="small">{{ Str::limit($r->comment, 80) }}</td>
                        <td>
                            @if($r->approved)
                                <span class="badge bg-success">Patvirtintas</span>
                            @else
                                <span class="badge bg-warning text-dark">Laukia</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.reviews.edit', $r) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('admin.reviews.destroy', $r) }}" class="d-inline" onsubmit="return confirm('Ištrinti atsiliepimą?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Atsiliepimų nėra.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $reviews->links('vendor.pagination.admin') }}</div>
@endsection
