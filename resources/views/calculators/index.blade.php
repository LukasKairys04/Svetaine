@extends('layouts.app')
@section('title', 'Skaičiuoklės')

@section('content')
<section class="container my-4">
    <h1 class="fw-bold mb-4"><i class="bi bi-calculator"></i> Skaičiuoklės</h1>
    <div class="row g-4">
        <div class="col-md-4">
            <a href="{{ route('calculators.bmi') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-speedometer2 display-4 text-primary"></i>
                        <h4 class="mt-3">BMI skaičiuoklė</h4>
                        <p class="text-muted">Apskaičiuok savo kūno masės indeksą.</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('calculators.nutrition') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-egg-fried display-4 text-success"></i>
                        <h4 class="mt-3">Mitybos skaičiuoklė</h4>
                        <p class="text-muted">Kalorijų ir makro poreikis pagal tikslą.</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('calculators.sport-plan') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-activity display-4 text-danger"></i>
                        <h4 class="mt-3">Sporto plano derintojas</h4>
                        <p class="text-muted">Treniruočių splitas pagal tavo tikslus.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</section>
@endsection
