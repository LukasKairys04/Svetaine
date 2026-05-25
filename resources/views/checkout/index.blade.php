@extends('layouts.app')

@section('title', 'Užsakymo patvirtinimas')



@section('content')

<section class="checkout-stripe-page">
<div class="container checkout-shell">

    <form method="POST" action="{{ route('checkout.place') }}" class="checkout-card-split">

        @csrf



        <aside class="checkout-aside">

            <a href="{{ route('cart.index') }}" class="checkout-back"><i class="bi bi-arrow-left"></i> Grįžti į krepšelį</a>

            <div class="eyebrow">FitShop checkout</div>

            <h2>Užbaigti apmokėjimą</h2>

            <div class="checkout-total">€{{ number_format($summary['total'], 2) }}</div>

            <p>Saugus demonstracinis atsiskaitymas Stripe stiliaus lange su tavo FitShop spalvomis.</p>



            <div class="checkout-items">

                @foreach($items as $it)

                    <div class="item-row">

                        <span>
                            @if($it->product->image)
                                <img src="{{ $it->product->image }}" alt="{{ $it->product->name }}">
                            @endif
                            <span>{{ $it->product->name }} <small>× {{ $it->qty }}</small></span>
                        </span>

                        <strong>€{{ number_format($it->subtotal, 2) }}</strong>

                    </div>

                @endforeach

            </div>



            <div class="checkout-breakdown">

                <div><span>Tarpinė suma</span><strong>€{{ number_format($summary['subtotal'], 2) }}</strong></div>

                @if($summary['discount'] > 0)

                    <div><span>Nuolaida</span><strong>−€{{ number_format($summary['discount'], 2) }}</strong></div>

                @endif

                <div><span>Pristatymas</span><strong>{{ $summary['shipping'] == 0 ? 'Nemokamas' : '€' . number_format($summary['shipping'], 2) }}</strong></div>

                <div class="final"><span>Iš viso šiandien</span><strong>€{{ number_format($summary['total'], 2) }}</strong></div>

            </div>

        </aside>



        <div class="checkout-main">

            <div class="checkout-heading">
                <span class="checkout-step">1 iš 2</span>
                <h1>Apmokėjimas</h1>
                <p>Įvesk kontaktinius duomenis ir pasirink mokėjimo būdą.</p>
            </div>



            <div class="checkout-section">

                <h5><i class="bi bi-person-lines-fill"></i> Atsiskaitymo informacija</h5>

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label small">Vardas, pavardė *</label>

                        <input type="text" name="billing_name" value="{{ old('billing_name', $user?->name) }}" class="form-control @error('billing_name') is-invalid @enderror" required>

                        @error('billing_name')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    </div>

                    <div class="col-md-6">

                        <label class="form-label small">El. paštas *</label>

                        <input type="email" name="billing_email" value="{{ old('billing_email', $user?->email) }}" class="form-control @error('billing_email') is-invalid @enderror" required>

                        @error('billing_email')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    </div>

                    <div class="col-md-6">

                        <label class="form-label small">Telefonas</label>

                        <input type="text" name="billing_phone" value="{{ old('billing_phone', $user?->phone) }}" class="form-control">

                    </div>

                    <div class="col-md-6">

                        <label class="form-label small">Šalis *</label>

                        <input type="text" name="billing_country" value="{{ old('billing_country', $user?->country ?? 'Lietuva') }}" class="form-control" required>

                    </div>

                    <div class="col-12">

                        <label class="form-label small">Adresas *</label>

                        <input type="text" name="billing_address" value="{{ old('billing_address', $user?->address) }}" class="form-control @error('billing_address') is-invalid @enderror" required>

                        @error('billing_address')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    </div>

                    <div class="col-md-6">

                        <label class="form-label small">Miestas *</label>

                        <input type="text" name="billing_city" value="{{ old('billing_city', $user?->city) }}" class="form-control" required>

                    </div>

                    <div class="col-md-6">

                        <label class="form-label small">Pašto kodas *</label>

                        <input type="text" name="billing_zip" value="{{ old('billing_zip', $user?->zip) }}" class="form-control" required>

                    </div>

                    <div class="col-12">

                        <label class="form-label small">Pastabos</label>

                        <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>

                    </div>

                </div>

            </div>



            <div class="checkout-section">

                <h5><i class="bi bi-credit-card"></i> Mokėjimo būdas</h5>

                <div class="payment-options">

                    @foreach(['card' => 'Kortelė', 'bank' => 'Pavedimas', 'paypal' => 'PayPal', 'cod' => 'Apmokėti atsiimant'] as $k => $label)

                        <label class="pay-option">

                            <input type="radio" name="payment_method" value="{{ $k }}" @checked(old('payment_method', 'card') === $k)>

                            <span>{{ $label }}</span>

                        </label>

                    @endforeach

                </div>

                <div class="alert checkout-demo small m-0 mt-3">
                    <i class="bi bi-shield-lock"></i> Pasirinkus <strong>Kortelė</strong> — būsite nukreipti į saugų Stripe mokėjimo puslapį.
                    Testavimui naudokite kortelės nr. <code>4242 4242 4242 4242</code>, bet kokią datą ir CVC.
                </div>

            </div>



            <button type="submit" class="btn btn-primary w-100 btn-lg checkout-submit">

                Apmokėti €{{ number_format($summary['total'], 2) }} <i class="bi bi-lock-fill"></i>

            </button>

        </div>

    </form>

</div>
</section>

@endsection

