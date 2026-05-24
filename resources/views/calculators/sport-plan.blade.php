@extends('layouts.app')
@section('title', 'Sporto plano derintojas')

@section('content')
<section class="container my-4" style="max-width:820px">
    <h1 class="fw-bold mb-4"><i class="bi bi-activity"></i> Sporto plano derintojas</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Patirtis</label>
                        <select name="level" class="form-select">
                            <option value="beginner">Pradedantysis</option>
                            <option value="intermediate" selected>Vidutinis</option>
                            <option value="advanced">Pažengęs</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tikslas</label>
                        <select name="goal" class="form-select">
                            <option value="strength">Jėga</option>
                            <option value="hypertrophy" selected>Raumenų masė</option>
                            <option value="endurance">Ištvermė</option>
                            <option value="weight_loss">Svorio metimas</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Dienų per savaitę</label>
                        <select name="days" class="form-select">
                            @foreach([3,4,5,6] as $d)<option value="{{ $d }}" @selected($d===4)>{{ $d }}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Laikas / treniruotei (min)</label>
                        <input type="number" name="time" value="60" class="form-control">
                    </div>
                </div>
                <button class="btn btn-danger mt-3">Sudaryti planą</button>
            </form>

            @if($result)
                <hr>
                <h4 class="mt-3">Tavo planas</h4>
                <div class="mb-3">
                    <span class="badge bg-danger">{{ ucfirst($result['level']) }}</span>
                    <span class="badge bg-primary">{{ ucfirst(str_replace('_',' ',$result['goal'])) }}</span>
                    <span class="badge bg-secondary">{{ $result['days'] }}x/sav.</span>
                    <span class="badge bg-info text-dark">{{ $result['time'] }} min</span>
                </div>

                <h6>Splitas</h6>
                <ol>
                    @foreach($result['split'] as $i => $day)
                        <li>Diena {{ $i + 1 }}: <strong>{{ $day }}</strong></li>
                    @endforeach
                </ol>

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <div class="card h-100 border-primary">
                            <div class="card-body"><h6>Kartojimų diapazonas</h6><div class="display-6 fw-bold text-primary">{{ $result['repsRange'] }}</div></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 border-warning">
                            <div class="card-body"><h6>Cardio</h6><p class="m-0">{{ $result['cardio'] }}</p></div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mt-3 small mb-0">
                    <i class="bi bi-info-circle"></i> Tai bazinis šablonas — naršyk <a href="{{ route('sports.index') }}">sporto planuose</a> ar pasikonsultuok su treneriu individualiam planui.
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
