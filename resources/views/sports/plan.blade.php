@extends('layouts.app')
@section('title', $plan->name)

@section('content')
<section class="container my-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('sports.index') }}" class="text-decoration-none text-muted">Sportas</a></li>
            @if($plan->sport)<li class="breadcrumb-item"><a href="{{ route('sports.show', $plan->sport->slug) }}" class="text-decoration-none text-muted">{{ $plan->sport->name }}</a></li>@endif
            <li class="breadcrumb-item active">{{ $plan->name }}</li>
        </ol>
    </nav>
<div class="sport-overview mb-4">
        <div>
            <div class="section-label">Sporto planas</div>
            <h2>{{ $plan->name }}</h2>
            <p class="lead">{{ $plan->description }}</p>
            <div class="d-flex gap-2 flex-wrap mt-2 mb-3">
                <span class="meta-pill level">{{ ucfirst($plan->level) }}</span>
                <span class="meta-pill">{{ $plan->duration_weeks }} sav.</span>
                <span class="meta-pill">{{ $plan->days_per_week }}× / sav.</span>
                <span class="meta-pill">Tikslas: {{ ucfirst($plan->goal) }}</span>
            </div>
            <div class="d-flex gap-2 flex-wrap mb-3">
                <a href="{{ route('sports.builder', ['from_plan' => $plan->slug]) }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Įkelti šį planą į kūrėją
                </a>
            </div>
            <div class="sport-stats">
                <div class="stat"><div class="stat-num">{{ $plan->exercises->count() }}</div><div class="stat-label">Pratimų</div></div>
                <div class="stat"><div class="stat-num">{{ $plan->exercises->groupBy('pivot.day')->count() }}</div><div class="stat-label">Dienų</div></div>
                <div class="stat"><div class="stat-num">{{ $plan->exercises->sum('pivot.sets') }}</div><div class="stat-label">Serijų</div></div>
            </div>
        </div>
        <div>
            @if($plan->image)<img src="{{ $plan->image }}" class="sport-overview-img" alt="{{ $plan->name }}">@endif
        </div>
    </div>
@php
        $byDay = $plan->exercises->groupBy('pivot.day');
        // Short muscle-group summary per day helps users scan the week.
        $dayFocus = [
            'Full Body (3×/sav.)' => [1 => 'Full Body A', 2 => 'Full Body B', 3 => 'Full Body C'],
            'Upper / Lower (4×/sav.)' => [1 => 'Upper (jėga)', 2 => 'Lower', 3 => 'Upper (tūris)', 4 => 'Lower'],
            'Bro Split (5×/sav.)' => [1 => 'Krūtinė', 2 => 'Nugara', 3 => 'Pečiai', 4 => 'Rankos', 5 => 'Kojos'],
        ];
        $labels = $dayFocus[$plan->name] ?? [];
    @endphp

    <div class="section-block" style="margin-top:2rem">
        <div class="section-label">Treniruočių planas</div>
        <h3>Savaitės grafikas</h3>

        @foreach($byDay as $day => $exercises)
            <div class="panel mb-3">
                <div class="panel-header">
                    <h5>Diena {{ $day }} @if(!empty($labels[$day]))<span class="text-muted fw-normal">· {{ $labels[$day] }}</span>@endif</h5>
                    <span class="text-muted small">{{ $exercises->count() }} pratimai · {{ $exercises->sum('pivot.sets') }} serijų</span>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle m-0">
                        <thead>
                            <tr class="small text-muted text-uppercase" style="letter-spacing:.05em;background:var(--fs-bg-soft)">
                                <th class="ps-4">Pratimas</th>
                                <th class="text-center">Serijos</th>
                                <th class="text-center">Kartojimai</th>
                                <th class="text-center">Poilsis</th>
                                <th class="pe-4 d-none d-md-table-cell">Pastabos</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($exercises as $ex)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-semibold text-dark">{{ $ex->name }}</span>
                                    <div class="d-md-none small text-muted mt-1">{{ $ex->pivot->notes }}</div>
                                </td>
                                <td class="text-center">{{ $ex->pivot->sets }}</td>
                                <td class="text-center">{{ $ex->pivot->reps }}</td>
                                <td class="text-center">{{ $ex->pivot->rest_seconds }}s</td>
                                <td class="pe-4 small text-muted d-none d-md-table-cell">{{ $ex->pivot->notes }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>

    <div class="cta-builder">
        <div>
            <h4>Nori pritaikyti šį planą sau?</h4>
            <p>Perkurk pratimus, keisk sets, reps ir svorius savo plano kūrėjyje.</p>
        </div>
        <a href="{{ route('sports.builder', ['from_plan' => $plan->slug]) }}" class="btn-cta">
            <i class="bi bi-tools"></i> Plano kūrėjas
        </a>
    </div>
</section>
@endsection
