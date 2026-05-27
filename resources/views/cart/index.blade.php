@extends('layouts.app')
@section('title', 'Krepšelis')

@section('content')
<section class="container my-4">
    <h1 class="fw-bold mb-4"><i class="bi bi-cart3"></i> Krepšelis</h1>

    @if($items->isEmpty())
        <div class="empty-cart-card mb-4">
            <i class="bi bi-cart-x"></i>
            <h4>Krepšelis tuščias</h4>
            <p class="text-muted">Pradėk apsipirkimą — žemiau pasirinkom populiariausias prekes tau.</p>
            <a href="{{ route('shop.index') }}" class="btn btn-primary">
                <i class="bi bi-bag me-1"></i>Eiti į parduotuvę
            </a>
        </div>

        @if($suggestions->isNotEmpty())
            <h5 class="fw-bold mb-3">Populiarios prekės</h5>
            <div class="row g-3 mb-4">
                @foreach($suggestions as $preke)
                    <div class="col-6 col-md-4 col-lg-3">
                        @include('partials.product-card', ['product' => $preke])
                    </div>
                @endforeach
            </div>
        @endif
    @else
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="table-responsive">
                        <table class="table align-middle m-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Prekė</th>
                                    <th>Kaina</th>
                                    <th style="width:150px">Kiekis</th>
                                    <th>Viso</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $it)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            @if($it->product->image)
                                                <img src="{{ $it->product->image }}" style="width:60px;height:60px;object-fit:cover" class="rounded" alt="{{ $it->product->name }}">
                                            @else
                                                <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width:60px;height:60px"><i class="bi bi-box-seam text-muted"></i></div>
                                            @endif
                                            <div>
                                                <a href="{{ route('product.show', $it->product->slug) }}" class="text-decoration-none text-dark fw-semibold">{{ $it->product->name }}</a>
                                                <div class="small text-muted">{{ $it->product->brand }}</div>
                                                <div class="small text-muted">Likutis: {{ $it->product->stock }} vnt.</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>€{{ number_format($it->product->effective_price, 2) }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('cart.update') }}" class="d-flex gap-1 js-cart-qty-form">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $it->product->id }}">
                                            <input type="number" name="qty" value="{{ $it->qty }}" min="0" max="{{ min(99, $it->product->stock) }}" class="form-control form-control-sm js-cart-qty" style="width:78px">
                                            <button class="btn btn-sm btn-outline-primary qty-sync-btn" type="submit"><i class="bi bi-arrow-repeat"></i></button>
                                        </form>
                                    </td>
                                    <td class="fw-bold">€{{ number_format($it->subtotal, 2) }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('cart.remove') }}">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $it->product->id }}">
                                            <button class="btn btn-sm cart-remove-btn" type="submit" aria-label="Pašalinti prekę"><i class="bi bi-trash3"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary mt-3 cart-continue-btn"><i class="bi bi-arrow-left"></i> Tęsti apsipirkimą</a>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm cart-summary-card">
                    <div class="card-body">
                        <h5 class="mb-3">Užsakymo suvestinė</h5>

                        <form method="POST" action="{{ route('cart.promo') }}" class="mb-3">
                            @csrf
                            <label class="form-label small fw-semibold">Nuolaidos kodas</label>
                            <div class="input-group input-group-sm">
                                <input type="text" name="code" value="{{ $summary['promo']?->code }}" class="form-control" placeholder="pvz. WELCOME10">
                                <button class="btn btn-outline-primary" type="submit">Pritaikyti</button>
                            </div>
                            @if($summary['promo'])
                                <div class="small text-success mt-1">
                                    <i class="bi bi-check-circle"></i> Kodas „{{ $summary['promo']->code }}“ aktyvus
                                </div>
                            @endif
                        </form>

                        <dl class="row mb-2">
                            <dt class="col-6">Tarpinė suma</dt><dd class="col-6 text-end">€{{ number_format($summary['subtotal'], 2) }}</dd>
                            @if($summary['discount'] > 0)
                                <dt class="col-6 text-success">Nuolaida</dt><dd class="col-6 text-end text-success">−€{{ number_format($summary['discount'], 2) }}</dd>
                            @endif
                            <dt class="col-6">Pristatymas</dt><dd class="col-6 text-end">{{ $summary['shipping'] == 0 ? 'Nemokamas' : '€' . number_format($summary['shipping'], 2) }}</dd>
                        </dl>
                        <hr>
                        <div class="d-flex justify-content-between fs-5 fw-bold mb-3 cart-summary-total">
                            <span>Iš viso</span>
                            <span>€{{ number_format($summary['total'], 2) }}</span>
                        </div>

                        @auth
                            <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 btn-lg">
                                Pereiti prie apmokėjimo <i class="bi bi-arrow-right"></i>
                            </a>
                        @else
                            <div class="alert alert-light border mb-3 py-2 small">
                                <i class="bi bi-info-circle text-primary"></i>
                                Užsakymui atlikti reikia prisijungti prie paskyros.
                            </div>
                            <a href="{{ route('login') }}?next={{ urlencode(route('checkout.index')) }}" class="btn btn-primary w-100 btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> Prisijungti ir pirkti
                            </a>
                            <div class="text-center mt-2">
                                <a href="{{ route('register') }}" class="small text-decoration-none">Dar neturi paskyros? Registruokis</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        @if($suggestions->isNotEmpty())
            <div class="mt-5 cart-suggestions">
                <h5 class="fw-bold mb-3">Kiti pasiūlymai tau</h5>
                <div class="row g-3">
                    @foreach($suggestions as $preke)
                        <div class="col-6 col-md-3">
                            @include('partials.product-card', ['product' => $preke])
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif
</section>
@endsection
