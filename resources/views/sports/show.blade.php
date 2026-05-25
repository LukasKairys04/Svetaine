@extends('layouts.app')
@section('title', $sport->name)

@section('content')
<section class="container my-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Pradžia</a></li>
            <li class="breadcrumb-item"><a href="{{ route('sports.index') }}" class="text-decoration-none text-muted">Sportas</a></li>
            <li class="breadcrumb-item active">{{ $sport->name }}</li>
        </ol>
    </nav>

    <div class="sport-overview mb-0">
        <div>
            <div class="section-label">Sporto šaka</div>
            <h2>{{ $sport->name }}</h2>
            <p class="lead">{{ $sport->short_description }}</p>
            <p class="text-muted">{{ $sport->description }}</p>
            <div class="sport-stats">
                <div class="stat"><div class="stat-num">{{ $sport->exercises->count() }}</div><div class="stat-label">Pratimų</div></div>
                <div class="stat"><div class="stat-num">{{ $sport->plans->count() }}</div><div class="stat-label">Planų</div></div>
            </div>
        </div>
        <div>
            @if($sport->image)<img src="{{ $sport->image }}" class="sport-overview-img" alt="{{ $sport->name }}">@endif
        </div>
    </div>

    @if($sport->plans->isNotEmpty())
        <div class="section-block">
            <div class="section-label">Programos</div>
            <h3>Planai</h3>
            <div class="row g-3">
                @foreach($sport->plans as $plan)
                    <div class="col-md-6 col-lg-4">
                        <div class="card-clean plan-card">
                            <div class="meta">
                                <span class="meta-pill level">{{ ucfirst($plan->level) }}</span>
                                <span class="meta-pill">{{ $plan->duration_weeks }} sav.</span>
                                <span class="meta-pill">{{ $plan->days_per_week }}× / sav.</span>
                            </div>
                            <h5>{{ $plan->name }}</h5>
                            <p>{{ Str::limit($plan->description, 110) }}</p>
                            <a href="{{ route('sports.plan', $plan->slug) }}" class="plan-link">Žiūrėti planą <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($sport->exercises->isNotEmpty())
        <div class="section-block">
            <div class="section-label">Katalogas</div>
            <h3>Pratimai ({{ $sport->exercises->count() }})</h3>
                        <div class="row g-3">
                @foreach($sport->exercises as $ex)
                    <div class="col-md-6 col-lg-4">
                        <div class="exercise-card-hover card-clean exercise-card" tabindex="0">
<div class="exercise-link mb-2 d-block">
                                {{ $ex->name }}<span class="english-name">{{ $ex->name_en ?? $ex->slug }}</span>
                            </div>
                            <div class="muscles">
                                @foreach(array_slice($ex->muscle_groups ?? [], 0, 4) as $m)
                                    <span class="muscle-tag">{{ $m }}</span>
                                @endforeach
                            </div>
                            <div class="difficulty {{ $ex->difficulty }}">{{ $ex->difficulty }}</div>
<div class="exercise-tooltip" role="tooltip">
                                @if($ex->image)
                                    <img src="{{ $ex->image }}" alt="{{ $ex->name }}" class="tooltip-img" loading="lazy">
                                @endif
                                <div class="tooltip-body">
                                    <div class="tooltip-head">
                                        <strong>{{ $ex->name }} @if($ex->name_en) / {{ $ex->name_en }} @endif</strong>
                                        <span class="badge-diff {{ $ex->difficulty }}">{{ $ex->difficulty }}</span>
                                    </div>
                                    @if($ex->short_description)
                                        <p class="tooltip-desc">{{ $ex->short_description }}</p>
                                    @endif
                                    @if(!empty($ex->muscle_groups))
                                        <div class="tooltip-section">
                                            <span class="tooltip-label">Raumenys</span>
                                            <div class="tooltip-muscles">
                                                @foreach($ex->muscle_groups as $m)
                                                    <span class="muscle-tag">{{ $m }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    @if(!empty($ex->benefits))
                                        <div class="tooltip-section">
                                            <span class="tooltip-label">Nauda</span>
                                            <ul class="tooltip-list">
                                                @foreach(array_slice($ex->benefits, 0, 3) as $b)
                                                    <li>{{ $b }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    @if($ex->how_to)
                                        <div class="tooltip-section">
                                            <span class="tooltip-label">Kaip atlikti</span>
                                            <p class="tooltip-how">{{ Str::limit($ex->how_to, 180) }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</section>
@endsection
