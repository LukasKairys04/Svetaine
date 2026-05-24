@extends('layouts.app')

@section('title', 'Užsakymo patvirtinimas')



@section('content')

<section class="container my-4 checkout-shell">

    <form method="POST" action="{{ route('checkout.place') }}" class="checkout-card-split">

        @csrf



        <aside class="checkout-aside">

            <div class="eyebrow">Užsakymo suvestinė</div>

            <h2>Užbaigti pirkimą</h2>

            <div class="checkout-total">€{{ number_format($summary['total'], 2) }}</div>

            <p>Užsakymas bus patvirtintas iškart po pateikimo. Mokėjimo integracija demonstracinė.</p>



            <div class="checkout-items">

                @foreach($items as $it)

                    <div class="item-row">

                        <span>{{ $it->product->name }} × {{ $it->qty }}</span>

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

            <h1>Apmokėjimas</h1>



            <div class="checkout-section">

                <h5>Atsiskaitymo informacija</h5>

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

                <h5>Mokėjimo būdas</h5>

                <div class="payment-options">

                    @foreach(['card' => 'Kortelė', 'bank' => 'Pavedimas', 'paypal' => 'PayPal', 'cod' => 'Apmokėti atsiimant'] as $k => $label)

                        <label class="pay-option">

                            <input type="radio" name="payment_method" value="{{ $k }}" @checked(old('payment_method', 'card') === $k)>

                            <span>{{ $label }}</span>

                        </label>

                    @endforeach

                </div>

                <div class="alert alert-info small m-0 mt-3">

                    <i class="bi bi-info-circle"></i> Demonstracinis režimas — realus mokėjimas nevyksta, užsakymas bus pažymėtas kaip apmokėtas.

                </div>

            </div>



            <button type="submit" class="btn btn-primary w-100 btn-lg checkout-submit">

                Patvirtinti užsakymą <i class="bi bi-arrow-right"></i>

            </button>

        </div>

    </form>

</section>

@endsection

