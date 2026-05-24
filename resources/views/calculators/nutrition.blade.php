@extends('layouts.app')
@section('title', 'Mitybos skaičiuoklė')

@section('content')
<section class="container my-4" style="max-width:820px">
    <h1 class="fw-bold mb-4"><i class="bi bi-egg-fried"></i> Mitybos skaičiuoklė</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Amžius</label>
                        <input type="number" name="age" value="{{ old('age', 25) }}" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Lytis</label>
                        <select name="gender" class="form-select">
                            <option value="male">Vyras</option>
                            <option value="female" @selected(old('gender')==='female')>Moteris</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Ūgis (cm)</label>
                        <input type="number" name="height" value="{{ old('height', 180) }}" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Svoris (kg)</label>
                        <input type="number" name="weight" value="{{ old('weight', 80) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Aktyvumas</label>
                        <select name="activity" class="form-select">
                            <option value="1.2">Sėdimas (mažai judėjimo)</option>
                            <option value="1.375">Lengvas (1–3 treniruotės/sav.)</option>
                            <option value="1.55" selected>Vidutinis (3–5/sav.)</option>
                            <option value="1.725">Aktyvus (6–7/sav.)</option>
                            <option value="1.9">Labai aktyvus / fizinis darbas</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tikslas</label>
                        <select name="goal" class="form-select">
                            <option value="lose">Svorio metimas</option>
                            <option value="maintain" selected>Palaikymas</option>
                            <option value="gain">Masės auginimas</option>
                        </select>
                    </div>
                </div>
                <button class="btn btn-success mt-3">Apskaičiuoti</button>
            </form>

            @if($result)
                <hr>
                <div class="row text-center g-3">
                    <div class="col-md-3">
                        <div class="border rounded p-3"><div class="small text-muted">BMR</div><div class="h4 m-0">{{ number_format($result['bmr']) }} kcal</div></div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3"><div class="small text-muted">TDEE</div><div class="h4 m-0">{{ number_format($result['tdee']) }} kcal</div></div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3 border-success"><div class="small text-muted">Tikslinė norma</div><div class="h3 m-0 text-success">{{ number_format($result['calories']) }} kcal</div></div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3"><div class="small text-muted">Makro</div>
                            <div>P: <strong>{{ $result['protein'] }}g</strong></div>
                            <div>C: <strong>{{ $result['carbs'] }}g</strong></div>
                            <div>F: <strong>{{ $result['fat'] }}g</strong></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
