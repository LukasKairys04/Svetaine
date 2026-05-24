@extends('layouts.app')
@section('title', $plan->name)

@section('content')
<section class="container my-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Pradžia</a></li>
            <li class="breadcrumb-item"><a href="{{ route('nutrition.index') }}" class="text-decoration-none text-muted">Mityba</a></li>
            <li class="breadcrumb-item active">{{ $plan->name }}</li>
        </ol>
    </nav>

    <div class="sport-overview mb-4">
        <div>
            <div class="section-label">Mitybos stilius</div>
            <h2>{{ $plan->name }}</h2>
            <p class="lead">{{ $plan->short_description }}</p>
            <p class="text-muted">{{ $plan->description }}</p>
        </div>
        <div>
            @if($plan->image)<img src="{{ $plan->image }}" class="sport-overview-img" alt="{{ $plan->name }}">@endif
        </div>
    </div>

    {{-- Pros / cons — symmetric 2-column grid --}}
    <div class="section-block">
        <div class="section-label">Už ir prieš</div>
        <h3>Pliusai ir minusai</h3>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card-clean" style="padding:1.5rem;border-top:3px solid #10b981">
                    <h5 style="color:#059669"><i class="bi bi-check2-circle"></i> Pliusai</h5>
                    <ul class="list-unstyled mb-0 mt-3">
                        @foreach(($plan->pros ?? []) as $p)
                            <li class="py-1" style="padding-left:1.5rem;position:relative">
                                <i class="bi bi-plus-circle text-success" style="position:absolute;left:0;top:.35rem"></i>
                                {{ $p }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card-clean" style="padding:1.5rem;border-top:3px solid #ef4444">
                    <h5 style="color:#b91c1c"><i class="bi bi-exclamation-circle"></i> Minusai</h5>
                    <ul class="list-unstyled mb-0 mt-3">
                        @foreach(($plan->cons ?? []) as $c)
                            <li class="py-1" style="padding-left:1.5rem;position:relative">
                                <i class="bi bi-dash-circle text-danger" style="position:absolute;left:0;top:.35rem"></i>
                                {{ $c }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Recommendations (optional) --}}
    @if($plan->recommendations->isNotEmpty())
        @php
            $healthy = $plan->recommendations->where('type', 'healthy_food');
            $supps = $plan->recommendations->where('type', 'supplement_alt');
        @endphp

        @if($healthy->isNotEmpty())
            <div class="section-block">
                <div class="section-label">Maisto idėjos</div>
                <h3>Sveiki produktai</h3>
                <div class="row g-3">
                    @foreach($healthy as $r)
                        <div class="col-md-6 col-lg-4">
                            <div class="card-clean" style="padding:1.25rem">
                                <h6 class="fw-bold">{{ $r->name }}</h6>
                                <p class="small text-muted mb-0">{{ $r->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($supps->isNotEmpty())
            <div class="section-block">
                <div class="section-label">Papildai</div>
                <h3>Rekomenduojami papildai</h3>
                <div class="row g-3">
                    @foreach($supps as $r)
                        <div class="col-md-6 col-lg-4">
                            <div class="card-clean" style="padding:1.25rem">
                                <h6 class="fw-bold">{{ $r->name }}</h6>
                                <p class="small text-muted mb-0">{{ $r->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif

    <div class="cta-builder">
        <div>
            <h4>Pasirinkai {{ $plan->name }}?</h4>
            <p>Susikurk savo kalorijų planą su šia dieta ir gauk tikslius makro tikslus.</p>
        </div>
        <a href="{{ route('nutrition.planner', ['diet' => $plan->slug]) }}" class="btn-cta">
            <i class="bi bi-clipboard-data"></i> Kurti kalorijų planą
        </a>
    </div>
</section>
@endsection
