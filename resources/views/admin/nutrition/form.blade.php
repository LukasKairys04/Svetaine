@extends('admin.layout')
@section('title', ($plan->exists ? 'Redaguoti' : 'Naujas') . ' mitybos planas')

@section('admin')
    @php $isNew = !$plan->exists; @endphp
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="fw-bold m-0">{{ $isNew ? 'Naujas mitybos planas' : 'Redaguoti planą' }}</h1>
        <a href="{{ route('admin.nutrition-plans.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Atgal</a>
    </div>

    <form method="POST" action="{{ $isNew ? route('admin.nutrition-plans.store') : route('admin.nutrition-plans.update', $plan) }}" class="admin-form-shell admin-form-card">
        @csrf @unless($isNew) @method('PUT') @endunless
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label small">Pavadinimas *</label><input type="text" name="name" value="{{ old('name', $plan->name) }}" class="form-control" required></div>
                <div class="col-md-3"><label class="form-label small">Slug</label><input type="text" name="slug" value="{{ old('slug', $plan->slug) }}" class="form-control"></div>
                <div class="col-md-3"><label class="form-label small">Tikslas</label>
                    <select name="goal_id" class="form-select"><option value="">—</option>
                        @foreach($goals as $g)<option value="{{ $g->id }}" @selected(old('goal_id', $plan->goal_id) == $g->id)>{{ $g->name }}</option>@endforeach
                    </select>
                </div>
                <div class="col-12"><label class="form-label small">Trumpas aprašymas</label><input type="text" name="short_description" value="{{ old('short_description', $plan->short_description) }}" class="form-control" placeholder="Trumpai: kam skirtas planas ir kokį rezultatą padeda pasiekti."></div>
                <div class="col-12"><label class="form-label small">Aprašymas</label><textarea name="description" class="form-control" rows="6" placeholder="Paprastai aprašyk: kas tai per mitybos planas, kam tinka ir kaip jį taikyti kasdien.">{{ old('description', $plan->description) }}</textarea></div>
                <div class="col-md-6"><label class="form-label small">Pliusai (po vieną eilutėje)</label><textarea name="pros" class="form-control" rows="5">{{ old('pros', is_array($plan->pros) ? implode("\n", $plan->pros) : '') }}</textarea></div>
                <div class="col-md-6"><label class="form-label small">Minusai (po vieną eilutėje)</label><textarea name="cons" class="form-control" rows="5">{{ old('cons', is_array($plan->cons) ? implode("\n", $plan->cons) : '') }}</textarea></div>
                <div class="col-md-10"><label class="form-label small">Paveikslėlio URL</label><input type="text" name="image" value="{{ old('image', $plan->image) }}" class="form-control"></div>
                <div class="col-md-2 d-flex align-items-end"><div class="form-check form-switch"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" @checked(old('is_active', $plan->is_active ?? true))><label class="form-check-label" for="is_active">Aktyvus</label></div></div>
            </div>
        </div>
        <div class="card-footer"><button class="btn btn-primary"><i class="bi bi-check2"></i> {{ $isNew ? 'Sukurti' : 'Išsaugoti' }}</button></div>
    </form>
@endsection
