@extends('admin.layout')

@section('title')

    <i class="bi bi-tag"></i> Kategorijos

@endsection



@section('admin')

    <div class="d-flex justify-content-end mb-3">

        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Pridėti</a>

    </div>

    <form method="GET" class="card p-3 mb-3 shadow-sm">
        <div class="row g-2">
            <div class="col-lg-4 col-md-6"><input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Ieškoti kategorijos..."></div>
            <div class="col-lg-3 col-md-6">
                <select name="type" class="form-select">
                    <option value="">Visi tipai</option>
                    <option value="product" @selected(request('type') === 'product')>Prekės</option>
                    <option value="nutrition" @selected(request('type') === 'nutrition')>Mityba</option>
                    <option value="sport" @selected(request('type') === 'sport')>Sportas</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-6">
                <select name="status" class="form-select">
                    <option value="">Visos būsenos</option>
                    <option value="active" @selected(request('status') === 'active')>Aktyvios</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Neaktyvios</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6 d-flex gap-2">
                <button class="btn btn-primary flex-fill">Filtruoti</button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Valyti</a>
            </div>
        </div>
    </form>



    <div class="card shadow-sm">

        <div class="table-responsive">

            <table class="table align-middle m-0">

                <thead class="table-light"><tr><th>Pavadinimas</th><th>Slug</th><th>Tipas</th><th>Produktų</th><th>Tvarka</th><th>Akt.</th><th></th></tr></thead>

                <tbody>

                @forelse($categories as $c)

                    <tr>

                        <td><strong>{{ $c->name }}</strong><div class="small text-muted">{{ $c->description }}</div></td>

                        <td><code>{{ $c->slug }}</code></td>

                        <td><span class="badge bg-secondary">{{ $c->type }}</span></td>

                        <td>{{ $c->products_count }}</td>

                        <td>{{ $c->sort_order }}</td>

                        <td>@if($c->is_active)<i class="bi bi-check-circle-fill text-success"></i>@else<i class="bi bi-x-circle text-muted"></i>@endif</td>

                        <td class="text-end">

                            <a href="{{ route('admin.categories.edit', $c) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>

                            <form method="POST" action="{{ route('admin.categories.destroy', $c) }}" class="d-inline" onsubmit="return confirm('Ištrinti?')">

                                @csrf @method('DELETE')

                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr><td colspan="7" class="text-center text-muted py-4">Kategorijų nėra.</td></tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <div class="mt-3">{{ $categories->links() }}</div>

@endsection

