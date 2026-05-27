@extends('admin.layout')
@section('title', 'Sandėlis')

@section('admin')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="h4 mb-1">Prekių sandėlis</h2>
            <p class="text-muted mb-0">Čia matomi visi produktų likučiai ir galima greitai pakeisti kiekį sandėlyje.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Pridėti naują produktą</a>
    </div>

    <form method="GET" class="card shadow-sm mb-3">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="small fw-semibold text-muted text-uppercase" style="letter-spacing:.05em">Filtrai</span>
                @if(request()->hasAny(['q','category_id','stock']))
                    <a href="{{ route('admin.inventory.index') }}" class="small text-danger text-decoration-none"><i class="bi bi-x-circle me-1"></i>Valyti filtrus</a>
                @endif
            </div>
            <div class="row g-2 align-items-center">
                <div class="col-md-5">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Ieškoti pagal pavadinimą, prekių ženklą arba slug...">
                </div>
                <div class="col-md-3">
                    <select name="category_id" class="form-select form-select-sm">
                        <option value="">Visos kategorijos</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="stock" class="form-select form-select-sm">
                        <option value="">Visi likučiai</option>
                        <option value="in" @selected(request('stock') === 'in')>Yra sandėlyje</option>
                        <option value="low" @selected(request('stock') === 'low')>Mažas likutis</option>
                        <option value="out" @selected(request('stock') === 'out')>Išparduota</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary btn-sm w-100">Filtruoti</button>
                </div>
            </div>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle m-0">
                <thead class="table-light">
                    <tr>
                        <th>Nuotrauka</th>
                        <th>Produktas</th>
                        <th>Kategorija</th>
                        <th>Dabartinis likutis</th>
                        <th>Būsena</th>
                        <th style="width:220px">Keisti likutį</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            @if($product->image)
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" style="width:54px;height:54px;object-fit:cover;border-radius:6px">
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light text-muted" style="width:54px;height:54px;border-radius:6px"><i class="bi bi-image"></i></div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $product->name }}</strong>
                            <div class="small text-muted">{{ $product->brand ?: 'Be prekių ženklo' }}</div>
                        </td>
                        <td>{{ $product->category?->name ?: 'Be kategorijos' }}</td>
                        <td><strong>{{ $product->stock }}</strong> vnt.</td>
                        <td>
                            @if($product->stock <= 0)
                                <span class="badge bg-danger">Išparduota</span>
                            @elseif($product->stock <= 5)
                                <span class="badge bg-warning text-dark">Mažas likutis</span>
                            @else
                                <span class="badge bg-success">Yra sandėlyje</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.inventory.update', $product) }}" class="d-flex gap-2">
                                @csrf
                                @method('PUT')
                                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" class="form-control form-control-sm" required>
                                <button class="btn btn-sm btn-outline-primary">Atnaujinti</button>
                            </form>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i> Redaguoti</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Produktų nerasta.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $products->links('vendor.pagination.admin') }}</div>
@endsection
