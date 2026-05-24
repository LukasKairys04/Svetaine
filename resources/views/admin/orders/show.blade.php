@extends('admin.layout')
@section('title', 'Užsakymas ' . $uzsakymas->order_number)

@section('admin')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="fw-bold m-0">Užsakymas #{{ $uzsakymas->order_number }}</h1>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Atgal</a>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h5>Prekės</h5>
                    <table class="table">
                        <thead><tr><th>Prekė</th><th>Kiekis</th><th>Kaina</th><th>Viso</th></tr></thead>
                        <tbody>
                        @foreach($uzsakymas->items as $i)
                            <tr>
                                <td>@if($i->product)<a href="{{ route('admin.products.edit', $i->product) }}">{{ $i->product_name }}</a>@else {{ $i->product_name }} @endif</td>
                                <td>{{ $i->qty }}</td>
                                <td>€{{ number_format($i->price, 2) }}</td>
                                <td>€{{ number_format($i->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <dl class="row mb-0 text-end">
                        <dt class="col-9">Tarpinė suma</dt><dd class="col-3">€{{ number_format($uzsakymas->subtotal, 2) }}</dd>
                        @if($uzsakymas->discount > 0)<dt class="col-9 text-success">Nuolaida</dt><dd class="col-3 text-success">−€{{ number_format($uzsakymas->discount, 2) }}</dd>@endif
                        <dt class="col-9">Pristatymas</dt><dd class="col-3">€{{ number_format($uzsakymas->shipping, 2) }}</dd>
                        <dt class="col-9 fs-5">Iš viso</dt><dd class="col-3 fs-5 fw-bold">€{{ number_format($uzsakymas->total, 2) }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Atsiskaitymo informacija</h5>
                    <address>
                        {{ $uzsakymas->billing_name }}<br>
                        <i class="bi bi-envelope"></i> {{ $uzsakymas->billing_email }} &nbsp;
                        @if($uzsakymas->billing_phone)<i class="bi bi-telephone"></i> {{ $uzsakymas->billing_phone }}@endif<br>
                        {{ $uzsakymas->billing_address }}<br>
                        {{ $uzsakymas->billing_zip }} {{ $uzsakymas->billing_city }}, {{ $uzsakymas->billing_country }}
                    </address>
                    @if($uzsakymas->notes)<div class="alert alert-warning small"><strong>Pastabos:</strong> {{ $uzsakymas->notes }}</div>@endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <form method="POST" action="{{ route('admin.orders.update', $uzsakymas) }}" class="card shadow-sm admin-status-card">
                @csrf @method('PUT')
                <div class="card-body">
                    <h5>Atsakymas / Būsena</h5>
                    <div class="mb-3"><label class="form-label small">Užsakymo būsena</label>
                        <select name="status" class="form-select">
                            @foreach([
                                'pending' => 'Naujas',
                                'paid' => 'Apmokėtas',
                                'processing' => 'Vykdomas',
                                'shipped' => 'Išsiųstas',
                                'completed' => 'Užbaigtas',
                                'cancelled' => 'Atšauktas',
                                'refunded' => 'Grąžintas'
                            ] as $s => $label)
                                <option value="{{ $s }}" @selected($uzsakymas->status === $s)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label small">Mokėjimo būsena</label>
                        <select name="payment_status" class="form-select">
                            @foreach([
                                'pending' => 'Laukiama',
                                'paid' => 'Apmokėta',
                                'failed' => 'Nepavyko',
                                'refunded' => 'Grąžinta'
                            ] as $s => $label)
                                <option value="{{ $s }}" @selected($uzsakymas->payment_status === $s)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="status-helper mb-3">
                        <div>Mokėjimo būdas: <strong>{{ $uzsakymas->payment_method }}</strong></div>
                        <div>Sukurta: {{ $uzsakymas->created_at->format('Y-m-d H:i') }}</div>
                        @if($uzsakymas->paid_at)<div>Apmokėta: {{ $uzsakymas->paid_at->format('Y-m-d H:i') }}</div>@endif
                    </div>
                    <button class="btn btn-primary w-100">Išsaugoti pakeitimus</button>
                </div>
            </form>
        </div>
    </div>
@endsection
