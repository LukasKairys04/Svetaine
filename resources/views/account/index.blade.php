@extends('layouts.app')
@section('title', 'Mano paskyra')

@section('content')
<section class="container my-4 account-page">

    {{-- Antraštė su vartotojo inicialais ir atsijungimo mygtuku --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="account-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div>
                <h1 class="fw-bold mb-0 fs-3">{{ $user->name }}</h1>
                <div class="text-muted small">{{ $user->email }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-box-arrow-right me-1"></i>Atsijungti
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Navigacija: Profilis | Užsakymai --}}
    <ul class="nav nav-pills mb-4 account-tabs" id="accountTabs">
        <li class="nav-item">
            <a class="nav-link {{ !request('tab') || request('tab') === 'profilis' ? 'active' : '' }}"
               href="{{ route('account.index') }}?tab=profilis">
                <i class="bi bi-person me-1"></i>Profilis
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('tab') === 'uzsakymai' ? 'active' : '' }}"
               href="{{ route('account.index') }}?tab=uzsakymai">
                <i class="bi bi-bag-check me-1"></i>Užsakymai
                @if($orders->count())
                    <span class="badge bg-primary ms-1">{{ $orders->count() }}</span>
                @endif
            </a>
        </li>
    </ul>

    {{-- PROFILIO SKYRIUS --}}
    @if(!request('tab') || request('tab') === 'profilis')

        <div class="card account-card mb-4">
            <div class="card-body">
                <h5>Asmeninė informacija</h5>
                <form method="POST" action="{{ route('account.update') }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small">Vardas</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">El. paštas</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Telefonas</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Šalis</label>
                            <input type="text" name="country" value="{{ old('country', $user->country) }}" class="form-control">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small">Adresas</label>
                            <input type="text" name="address" value="{{ old('address', $user->address) }}" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">Pašto kodas</label>
                            <input type="text" name="zip" value="{{ old('zip', $user->zip) }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Miestas</label>
                            <input type="text" name="city" value="{{ old('city', $user->city) }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Lytis</label>
                            <select name="gender" class="form-select">
                                <option value="">—</option>
                                <option value="male"   @selected($user->gender === 'male')>Vyras</option>
                                <option value="female" @selected($user->gender === 'female')>Moteris</option>
                                <option value="other"  @selected($user->gender === 'other')>Kita</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Gimimo data</label>
                            <input type="date" name="birthdate" value="{{ old('birthdate', optional($user->birthdate)->format('Y-m-d')) }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Ūgis (cm)</label>
                            <input type="number" step="0.1" name="height_cm" value="{{ old('height_cm', $user->height_cm) }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Svoris (kg)</label>
                            <input type="number" step="0.1" name="weight_kg" value="{{ old('weight_kg', $user->weight_kg) }}" class="form-control">
                        </div>
                    </div>
                    <button class="btn btn-primary mt-3">Išsaugoti</button>
                </form>
            </div>
        </div>

        <div class="card account-card">
            <div class="card-body">
                <h5>Slaptažodžio keitimas</h5>
                <form method="POST" action="{{ route('account.password') }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small">Dabartinis slaptažodis</label>
                            <input type="password" name="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror" required>
                            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">Naujas slaptažodis</label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">Pakartokite</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <button class="btn btn-outline-primary mt-3">Keisti slaptažodį</button>
                </form>
            </div>
        </div>

    {{-- UŽSAKYMŲ SKYRIUS --}}
    @elseif(request('tab') === 'uzsakymai')

        @if($orders->isEmpty())
            <div class="card account-card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-bag-x fs-1 text-muted mb-3 d-block"></i>
                    <h5 class="text-muted">Dar nėra užsakymų</h5>
                    <p class="text-muted small mb-3">Pirmą kartą apsipirkęs, čia matysi visą savo istoriją.</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-bag me-1"></i>Pereiti į parduotuvę
                    </a>
                </div>
            </div>
        @else
            <div class="card account-card">
                <div class="table-responsive">
                    <table class="table m-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Užsakymo Nr.</th>
                                <th>Data</th>
                                <th>Suma</th>
                                <th>Būsena</th>
                                <th>Mokėjimas</th>
                                <th class="pe-4"></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $o)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $o->order_number }}</td>
                                <td class="text-muted small">{{ $o->created_at->format('Y-m-d') }}</td>
                                <td class="fw-semibold">€{{ number_format($o->total, 2) }}</td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending'    => 'secondary',
                                            'paid'       => 'primary',
                                            'processing' => 'info',
                                            'shipped'    => 'warning',
                                            'completed'  => 'success',
                                            'cancelled'  => 'danger',
                                            'refunded'   => 'dark',
                                        ];
                                        $statusLabels = [
                                            'pending'    => 'Naujas',
                                            'paid'       => 'Apmokėtas',
                                            'processing' => 'Vykdomas',
                                            'shipped'    => 'Išsiųstas',
                                            'completed'  => 'Užbaigtas',
                                            'cancelled'  => 'Atšauktas',
                                            'refunded'   => 'Grąžintas',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$o->status] ?? 'secondary' }}">
                                        {{ $statusLabels[$o->status] ?? ucfirst($o->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $o->payment_status === 'paid' ? 'success' : 'secondary' }}">
                                        {{ $o->payment_status === 'paid' ? 'Apmokėta' : ucfirst($o->payment_status) }}
                                    </span>
                                </td>
                                <td class="pe-4">
                                    <a href="{{ route('account.order', $o) }}" class="btn btn-sm btn-outline-primary">
                                        Peržiūrėti
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    @endif

</section>
@endsection
