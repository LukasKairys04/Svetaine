@extends('layouts.app')
@section('title', 'Pagalba')

@section('content')
<section class="container my-4">

    {{-- Page title --}}
    <div class="mb-4">
        <div class="section-label">Klientų aptarnavimas</div>
        <h1 class="fw-bold mb-1">Pagalba</h1>
        <p class="text-muted mb-0" style="max-width:640px">
            Turi klausimą apie užsakymą, pristatymą ar produktą? Čia rasi atsakymus į dažniausiai užduodamus klausimus arba gali susisiekti tiesiogiai.
        </p>
    </div>

    {{-- Quick contact tiles --}}
    <div class="support-contact-grid mb-5">
        <div class="support-tile">
            <div class="icon"><i class="bi bi-envelope"></i></div>
            <div class="label">El. paštas</div>
            <a href="mailto:info@fitshop.lt" class="value">info@fitshop.lt</a>
            <div class="hint">Atsakome per 24 val. darbo dienomis</div>
        </div>
        <div class="support-tile">
            <div class="icon"><i class="bi bi-telephone"></i></div>
            <div class="label">Telefonas</div>
            <a href="tel:+37060000000" class="value">+370 600 00000</a>
            <div class="hint">I–V 9:00–17:00</div>
        </div>
        <div class="support-tile">
            <div class="icon"><i class="bi bi-geo-alt"></i></div>
            <div class="label">Adresas</div>
            <span class="value">Gedimino pr. 1, Vilnius</span>
            <div class="hint">Atsiėmimas iš anksto susitarus</div>
        </div>
    </div>

    {{-- Two-column: FAQ + contact form --}}
    <div class="row g-4">
        {{-- FAQ --}}
        <div class="col-lg-7">
            <h3 class="mb-3">Dažniausiai užduodami klausimai</h3>

            <div class="faq">
                <details>
                    <summary>Kaip greitai ateina užsakymas?</summary>
                    <div class="body">Standartinis pristatymas Lietuvoje — 1–3 darbo dienos. Užsakymus apmokestintus iki 15:00 siunčiame tą pačią dieną.</div>
                </details>
                <details>
                    <summary>Kiek kainuoja pristatymas?</summary>
                    <div class="body">Pristatymas į paštomatą — 2.99 €, kurjeriu į namus — 4.99 €. Užsakymams virš 50 € — pristatymas nemokamas.</div>
                </details>
                <details>
                    <summary>Ar galiu grąžinti prekę?</summary>
                    <div class="body">Taip, per 14 dienų nuo gavimo gali grąžinti neatplėštas pakuotes be jokio paaiškinimo. Atidarytus maisto papildus grąžinti negalima dėl higienos.</div>
                </details>
                <details>
                    <summary>Ar produktai yra originalūs?</summary>
                    <div class="body">Visas prekes perkame tiesiogiai iš oficialių gamintojų arba įgaliotų platintojų. Kiekvienas produktas turi galiojantį partijos numerį ir galiojimo datą.</div>
                </details>
                <details>
                    <summary>Kaip pasinaudoti nuolaidos kodu?</summary>
                    <div class="body">Krepšelio puslapyje rasi lauką „Nuolaidos kodas". Įvesk savo kodą ir paspausk „Pritaikyti" prieš pereidamas į apmokėjimą.</div>
                </details>
                <details>
                    <summary>Kokie apmokėjimo būdai priimami?</summary>
                    <div class="body">Bankinis pavedimas, Paysera, mokėjimas kortele (Visa/Mastercard), taip pat apmokėjimas grynaisiais atsiimant.</div>
                </details>
                <details>
                    <summary>Ar siunčiate į užsienį?</summary>
                    <div class="body">Šiuo metu dirbame tik su Lietuvos adresais. Siuntimo į ES šalis galimybę ruošiame.</div>
                </details>
            </div>
        </div>

        {{-- Contact form --}}
        <div class="col-lg-5">
            <div class="support-form-card">
                <h4 class="mb-1">Parašyk mums</h4>
                <p class="text-muted small mb-3">Neradai atsakymo? Užpildyk formą ir atsakysime į tavo el. paštą.</p>

                <form method="POST" action="{{ route('support.submit') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Vardas</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()?->name) }}"
                               class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">El. paštas</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}"
                               class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Tema</label>
                        <select name="subject" class="form-select @error('subject') is-invalid @enderror" required>
                            <option value="">— Pasirink —</option>
                            <option value="Užsakymas" @selected(old('subject') === 'Užsakymas')>Užsakymas</option>
                            <option value="Pristatymas" @selected(old('subject') === 'Pristatymas')>Pristatymas</option>
                            <option value="Grąžinimas" @selected(old('subject') === 'Grąžinimas')>Grąžinimas</option>
                            <option value="Produktas" @selected(old('subject') === 'Produktas')>Klausimas apie produktą</option>
                            <option value="Kita" @selected(old('subject') === 'Kita')>Kita</option>
                        </select>
                        @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Žinutė</label>
                        <textarea name="message" class="form-control @error('message') is-invalid @enderror" rows="5"
                                  placeholder="Aprašyk savo klausimą ar problemą..." required>{{ old('message') }}</textarea>
                        @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-send"></i> Siųsti žinutę
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
