@extends('admin.layout')
@section('title', 'Produktai')

@section('admin')
    <div class="d-flex justify-content-end align-items-center mb-3">
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Pridėti</a>
    </div>

    <form method="GET" class="card p-3 mb-3 shadow-sm">
        <div class="row g-2">
            <div class="col-lg-3 col-md-6"><input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Ieškoti pagal pavadinimą, brand, slug..."></div>
            <div class="col-lg-2 col-md-6">
                <select name="category_id" class="form-select">
                    <option value="">Visos kategorijos</option>
                    @foreach($categories as $c)<option value="{{ $c->id }}" @selected(request('category_id') == $c->id)>{{ $c->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <select name="status" class="form-select">
                    <option value="">Visos būsenos</option>
                    <option value="active" @selected(request('status') === 'active')>Aktyvūs</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Neaktyvūs</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <select name="featured" class="form-select">
                    <option value="">Visi produktai</option>
                    <option value="1" @selected(request('featured') === '1')>Rekomenduojami</option>
                    <option value="0" @selected(request('featured') === '0')>Nerekomenduojami</option>
                </select>
            </div>
            <div class="col-lg-1 col-md-4">
                <select name="stock" class="form-select">
                    <option value="">Likutis</option>
                    <option value="in" @selected(request('stock') === 'in')>Yra</option>
                    <option value="out" @selected(request('stock') === 'out')>Nėra</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-12 d-flex gap-2">
                <button class="btn btn-primary flex-fill">Filtruoti</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Valyti</a>
            </div>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle m-0">
                <thead class="table-light"><tr><th></th><th>Pavadinimas</th><th>Kategorija</th><th>Kaina</th><th>Likutis</th><th>Rating</th><th>Akt.</th><th></th></tr></thead>
                <tbody>
                @forelse($products as $p)
                    <tr>
                        <td>@if($p->image)<img src="{{ $p->image }}" style="width:50px;height:50px;object-fit:cover;border-radius:4px">@endif</td>
                        <td>
                            <strong>{{ $p->name }}</strong>
                            <div class="small text-muted">{{ $p->brand }}</div>
                        </td>
                        <td>{{ $p->category?->name }}</td>
                        <td>
                            @if($p->sale_price)
                                <span class="text-decoration-line-through text-muted small">€{{ number_format($p->price, 2) }}</span>
                                <span class="text-danger fw-bold">€{{ number_format($p->sale_price, 2) }}</span>
                            @else
                                €{{ number_format($p->price, 2) }}
                            @endif
                        </td>
                        <td>
                            @if($p->stock > 0)
                                {{ $p->stock }}
                            @else
                                <span class="badge bg-danger">0</span>
                            @endif
                        </td>
                        <td>{{ number_format($p->rating, 1) }} ⭐ ({{ $p->rating_count }})</td>
                        <td>@if($p->is_active)<i class="bi bi-check-circle-fill text-success"></i>@else<i class="bi bi-x-circle text-muted"></i>@endif</td>
                        <td class="text-end">
                            <a href="{{ route('product.show', $p->slug) }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.products.edit', $p) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('admin.products.destroy', $p) }}" class="d-inline" onsubmit="return confirm('Ištrinti produktą?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Produktų nerasta.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $products->links() }}</div>
@endsection
