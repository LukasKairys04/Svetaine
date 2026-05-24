@extends('layouts.app')

@section('title', 'Užsakymas priimtas')



@section('content')

<section class="container my-5">

    <div class="text-center mb-4">

        <i class="bi bi-check-circle-fill text-success" style="font-size:4rem"></i>

        <h1 class="fw-bold mt-3">Ačiū už užsakymą!</h1>

        <p class="lead text-muted">Jūsų užsakymas <strong>#{{ $order->order_number }}</strong> sėkmingai priimtas.</p>

    </div>



    <div class="card shadow-sm mx-auto" style="max-width:700px">

        <div class="card-body">

            <div class="row mb-3">

                <div class="col-sm-6">

                    <div class="small text-muted">Užsakymo Nr.</div>

                    <strong>{{ $order->order_number }}</strong>

                </div>

                <div class="col-sm-6">

                    <div class="small text-muted">Būsena</div>

                    <span class="badge bg-success">{{ ucfirst($order->status) }}</span>

                    <span class="badge bg-primary">{{ ucfirst($order->payment_status) }}</span>

                </div>

            </div>

            <hr>

            <h6>Prekės</h6>

            <ul class="list-group list-group-flush mb-3">

                @foreach($order->items as $i)

                    <li class="list-group-item d-flex justify-content-between">

                        <span>{{ $i->product_name }} × {{ $i->qty }}</span>

                        <strong>€{{ number_format($i->subtotal, 2) }}</strong>

                    </li>

                @endforeach

            </ul>

            <dl class="row mb-0">

                <dt class="col-8">Tarpinė suma</dt><dd class="col-4 text-end">€{{ number_format($order->subtotal, 2) }}</dd>

                @if($order->discount > 0)

                    <dt class="col-8 text-success">Nuolaida</dt><dd class="col-4 text-end text-success">−€{{ number_format($order->discount, 2) }}</dd>

                @endif

                <dt class="col-8">Pristatymas</dt><dd class="col-4 text-end">€{{ number_format($order->shipping, 2) }}</dd>

                <dt class="col-8 fs-5">Iš viso</dt><dd class="col-4 text-end fs-5 fw-bold">€{{ number_format($order->total, 2) }}</dd>

            </dl>

        </div>

    </div>



    <div class="text-center mt-4">

        <a href="{{ route('shop.index') }}" class="btn btn-primary">Tęsti apsipirkimą</a>

        @auth

            <a href="{{ route('account.orders') }}" class="btn btn-outline-secondary">Mano užsakymai</a>

        @endauth

    </div>

</section>

@endsection

