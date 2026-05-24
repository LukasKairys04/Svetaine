@extends('layouts.app')
@section('title', 'Užsakymas ' . $uzsakymas->order_number)

@section('content')
<section class="container my-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('account.index') }}">Paskyra</a></li>
            <li class="breadcrumb-item"><a href="{{ route('account.orders') }}">Užsakymai</a></li>
            <li class="breadcrumb-item active">{{ $uzsakymas->order_number }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between flex-wrap mb-3">
                        <div>
                            <a href="{{ route('account.index') }}?tab=uzsakymai" class="btn btn-outline-secondary btn-sm mb-2">
                                <i class="bi bi-arrow-left me-1"></i>Grįžti atgal
                            </a>
                            <h4 class="mt-1">Užsakymas #{{ $uzsakymas->order_number }}</h4>
                            <div class="text-muted small">{{ $uzsakymas->created_at->format('Y-m-d H:i') }}</div>
                        </div>
                        <div class="d-flex gap-2 align-items-start">
                            <span class="badge bg-primary fs-6">{{ ucfirst($uzsakymas->status) }}</span>
                            <span class="badge bg-{{ $uzsakymas->payment_status === 'paid' ? 'success' : 'secondary' }} fs-6">{{ ucfirst($uzsakymas->payment_status) }}</span>
                        </div>
                    </div>

                    <h6 class="mt-3">Prekės</h6>
                    <table class="table">
                        <thead><tr><th>Prekė</th><th>Kiekis</th><th>Kaina</th><th>Viso</th></tr></thead>
                        <tbody>
                        @foreach($uzsakymas->items as $i)
                            <tr><td>{{ $i->product_name }}</td><td>{{ $i->qty }}</td><td>€{{ number_format($i->price, 2) }}</td><td>€{{ number_format($i->subtotal, 2) }}</td></tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Pristatymas</h6>
                            <address class="small">
                                {{ $uzsakymas->billing_name }}<br>
                                {{ $uzsakymas->billing_address }}<br>
                                {{ $uzsakymas->billing_zip }} {{ $uzsakymas->billing_city }}<br>
                                {{ $uzsakymas->billing_country }}<br>
                                <i class="bi bi-envelope"></i> {{ $uzsakymas->billing_email }}<br>
                                @if($uzsakymas->billing_phone)<i class="bi bi-telephone"></i> {{ $uzsakymas->billing_phone }}@endif
                            </address>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <dl class="row mb-0">
                                <dt class="col-6">Tarpinė suma</dt><dd class="col-6">€{{ number_format($uzsakymas->subtotal, 2) }}</dd>
                                @if($uzsakymas->discount > 0)<dt class="col-6 text-success">Nuolaida</dt><dd class="col-6 text-success">−€{{ number_format($uzsakymas->discount, 2) }}</dd>@endif
                                <dt class="col-6">Pristatymas</dt><dd class="col-6">€{{ number_format($uzsakymas->shipping, 2) }}</dd>
                                <dt class="col-6 fs-5">Iš viso</dt><dd class="col-6 fs-5 fw-bold">€{{ number_format($uzsakymas->total, 2) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
