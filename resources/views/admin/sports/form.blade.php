@extends('admin.layout')
@section('title', ($plan->exists ? 'Redaguoti' : 'Naujas') . ' sporto planas')

@section('admin')
    @php $isNew = !$plan->exists; @endphp
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="fw-bold m-0">{{ $isNew ? 'Naujas sporto planas' : 'Redaguoti planą' }}</h1>
        <a href="{{ route('admin.sport-plans.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Atgal</a>
    </div>

    <form method="POST" action="{{ $isNew ? route('admin.sport-plans.store') : route('admin.sport-plans.update', $plan) }}" class="admin-form-shell admin-form-card">
        @csrf @unless($isNew) @method('PUT') @endunless
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label small">Pavadinimas *</label><input type="text" name="name" value="{{ old('name', $plan->name) }}" class="form-control" required></div>
                <div class="col-md-3"><label class="form-label small">Slug</label><input type="text" name="slug" value="{{ old('slug', $plan->slug) }}" class="form-control"></div>
                <div class="col-md-3"><label class="form-label small">Sporto šaka</label>
                    <select name="sport_id" class="form-select"><option value="">—</option>
                        @foreach($sports as $s)<option value="{{ $s->id }}" @selected(old('sport_id', $plan->sport_id) == $s->id)>{{ $s->name }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-3"><label class="form-label small">Lygis *</label>
                    <select name="level" class="form-select">@foreach(['beginner','intermediate','advanced'] as $l)<option value="{{ $l }}" @selected(old('level', $plan->level) === $l)>{{ ucfirst($l) }}</option>@endforeach</select>
                </div>
                <div class="col-md-3"><label class="form-label small">Tikslas *</label>
                    <select name="goal" class="form-select">@foreach(['strength','hypertrophy','endurance','weight_loss','general'] as $g)<option value="{{ $g }}" @selected(old('goal', $plan->goal) === $g)>{{ ucfirst(str_replace('_',' ',$g)) }}</option>@endforeach</select>
                </div>
                <div class="col-md-3"><label class="form-label small">Trukmė (sav.) *</label><input type="number" name="duration_weeks" value="{{ old('duration_weeks', $plan->duration_weeks) }}" class="form-control" required></div>
                <div class="col-md-3"><label class="form-label small">Dienų/sav. *</label><input type="number" name="days_per_week" value="{{ old('days_per_week', $plan->days_per_week) }}" class="form-control" required></div>
                <div class="col-12"><label class="form-label small">Aprašymas</label><textarea name="description" rows="5" class="form-control" placeholder="Trumpai: kas tai per planas, kam tinka ir kokį rezultatą padeda pasiekti.">{{ old('description', $plan->description) }}</textarea></div>
                <div class="col-md-10"><label class="form-label small">Paveikslėlio URL</label><input type="text" name="image" value="{{ old('image', $plan->image) }}" class="form-control"></div>
                <div class="col-md-2 d-flex align-items-end"><div class="form-check form-switch"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" @checked(old('is_active', $plan->is_active ?? true))><label class="form-check-label" for="is_active">Aktyvus</label></div></div>
            </div>
        </div>
        <div class="card-footer"><button class="btn btn-primary"><i class="bi bi-check2"></i> {{ $isNew ? 'Sukurti' : 'Išsaugoti' }}</button></div>
    </form>
@endsection
