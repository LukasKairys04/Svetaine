@extends('layouts.app')
@section('title', 'BMI skaičiuoklė')

@section('content')
<section class="container my-4" style="max-width:720px">
    <h1 class="fw-bold mb-4"><i class="bi bi-speedometer2"></i> BMI skaičiuoklė</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Ūgis (cm)</label>
                        <input type="number" name="height" step="0.1" value="{{ old('height', request('height')) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Svoris (kg)</label>
                        <input type="number" name="weight" step="0.1" value="{{ old('weight', request('weight')) }}" class="form-control" required>
                    </div>
                </div>
                <button class="btn btn-primary mt-3">Apskaičiuoti</button>
            </form>

            @if($result)
                <hr>
                <div class="text-center">
                    <div class="display-3 fw-bold">{{ $result['bmi'] }}</div>
                    <span class="badge bg-{{ $result['color'] }} fs-6">{{ $result['label'] }}</span>
                </div>
                <div class="mt-4 small text-muted">
                    <strong>Kategorijos:</strong><br>
                    &lt; 18.5 — per mažas svoris<br>
                    18.5–24.9 — normalus<br>
                    25–29.9 — antsvoris<br>
                    ≥ 30 — nutukimas
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
