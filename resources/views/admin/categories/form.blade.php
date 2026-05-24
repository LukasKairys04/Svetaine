@extends('admin.layout')
@section('title', ($category->exists ? 'Redaguoti' : 'Nauja') . ' kategorija')

@section('admin')
    @php $isNew = !$category->exists; @endphp
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="fw-bold m-0">{{ $isNew ? 'Nauja kategorija' : 'Redaguoti: ' . $category->name }}</h1>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Atgal</a>
    </div>

    <form method="POST" action="{{ $isNew ? route('admin.categories.store') : route('admin.categories.update', $category) }}" class="admin-form-shell admin-form-card">
        @csrf @unless($isNew) @method('PUT') @endunless
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label small">Pavadinimas *</label><input type="text" name="name" value="{{ old('name', $category->name) }}" class="form-control @error('name') is-invalid @enderror" required>@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4"><label class="form-label small">Slug</label><input type="text" name="slug" value="{{ old('slug', $category->slug) }}" class="form-control"></div>
                <div class="col-md-2"><label class="form-label small">Tipas *</label>
                    <select name="type" class="form-select">
                        @foreach(['product' => 'Prekės', 'nutrition' => 'Mityba', 'sport' => 'Sportas'] as $k => $v)
                            <option value="{{ $k }}" @selected(old('type', $category->type) === $k)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12"><label class="form-label small">Aprašymas</label><textarea name="description" rows="2" class="form-control">{{ old('description', $category->description) }}</textarea></div>
                <div class="col-md-6"><label class="form-label small">Paveikslėlio URL</label><input type="text" name="image" value="{{ old('image', $category->image) }}" class="form-control"></div>
                <div class="col-md-3"><label class="form-label small">Tvarka</label><input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}" class="form-control"></div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="form-check form-switch"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" @checked(old('is_active', $category->is_active ?? true))><label class="form-check-label" for="is_active">Aktyvi</label></div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-primary"><i class="bi bi-check2"></i> {{ $isNew ? 'Sukurti' : 'Išsaugoti' }}</button>
        </div>
    </form>
@endsection
