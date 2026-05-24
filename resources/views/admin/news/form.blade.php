@extends('admin.layout')
@section('title', ($item->exists ? 'Redaguoti' : 'Nauja') . ' naujiena')

@section('admin')
    @php $isNew = !$item->exists; @endphp
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="fw-bold m-0">{{ $isNew ? 'Nauja naujiena' : 'Redaguoti naujieną' }}</h1>
        <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Atgal</a>
    </div>

    <form method="POST" action="{{ $isNew ? route('admin.news.store') : route('admin.news.update', $item) }}" class="card shadow-sm">
        @csrf @unless($isNew) @method('PUT') @endunless
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-8"><label class="form-label small">Pavadinimas *</label><input type="text" name="title" value="{{ old('title', $item->title) }}" class="form-control" required></div>
                <div class="col-md-4"><label class="form-label small">Slug</label><input type="text" name="slug" value="{{ old('slug', $item->slug) }}" class="form-control"></div>
                <div class="col-12"><label class="form-label small">Trumpas aprašymas</label><textarea name="excerpt" class="form-control" rows="2">{{ old('excerpt', $item->excerpt) }}</textarea></div>
                <div class="col-12"><label class="form-label small">Turinys (HTML leidžiamas)</label><textarea name="content" class="form-control" rows="8">{{ old('content', $item->content) }}</textarea></div>
                <div class="col-md-8"><label class="form-label small">Paveikslėlio URL</label><input type="text" name="image" value="{{ old('image', $item->image) }}" class="form-control"></div>
                <div class="col-md-4"><label class="form-label small">Publikavimo data</label><input type="datetime-local" name="published_at" value="{{ old('published_at', optional($item->published_at)->format('Y-m-d\TH:i')) }}" class="form-control"></div>
                <div class="col-12"><div class="form-check form-switch"><input type="checkbox" name="is_published" value="1" class="form-check-input" id="pub" @checked(old('is_published', $item->is_published ?? true))><label class="form-check-label" for="pub">Publikuota</label></div></div>
            </div>
        </div>
        <div class="card-footer"><button class="btn btn-primary"><i class="bi bi-check2"></i> {{ $isNew ? 'Sukurti' : 'Išsaugoti' }}</button></div>
    </form>
@endsection
