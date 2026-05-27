@extends('layouts.app')
@section('title', 'Apie mus')

@section('content')
<section class="container my-4">
    <div class="mb-5">
        <div class="section-label">Apie parduotuvę</div>
        <h1 class="fw-bold mb-2">Apie mus</h1>
        <p class="text-muted mb-0" style="max-width:720px">
            PapildaiOnline yra demonstracinė sporto papildų e. parduotuvė, kurioje galima rasti produktus sportui, mitybos planus, treniruočių idėjas ir skaičiuokles kasdieniam progresui stebėti.
        </p>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="support-tile h-100">
                <div class="icon"><i class="bi bi-bag-check"></i></div>
                <div class="label">Produktai</div>
                <div class="value">Sporto papildai</div>
                <div class="hint">Baltymai, kreatinas, vitaminai ir kitos aktyviam gyvenimui skirtos prekės.</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="support-tile h-100">
                <div class="icon"><i class="bi bi-egg"></i></div>
                <div class="label">Mityba</div>
                <div class="value">Planai ir skaičiuoklės</div>
                <div class="hint">BMI, kalorijų poreikis ir mitybos planų peržiūra pagal tikslą.</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="support-tile h-100">
                <div class="icon"><i class="bi bi-heart-pulse"></i></div>
                <div class="label">Sportas</div>
                <div class="value">Treniruočių planai</div>
                <div class="hint">Pratimai, sporto planai ir plano kūrėjas pagal pasirinktą tikslą.</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <h3 class="mb-3">Kaip naudotis svetaine?</h3>
            <div class="faq">
                <details open>
                    <summary>Kaip rasti tinkamą produktą?</summary>
                    <div class="body">Parduotuvėje galima naudoti paiešką, kategorijų filtrus, kainos rėžius, prekių ženklus ir rūšiavimą pagal populiarumą ar kainą.</div>
                </details>
                <details>
                    <summary>Kaip veikia sandėlio likutis?</summary>
                    <div class="body">Prie produkto matomas turimas kiekis. Į krepšelį negalima įsidėti daugiau vienetų nei realiai yra sandėlyje.</div>
                </details>
                <details>
                    <summary>Kada galima palikti atsiliepimą?</summary>
                    <div class="body">Atsiliepimą galima palikti paskyros užsakymo viduje, kai užsakymas yra apmokėtas ir pažymėtas kaip gautas.</div>
                </details>
                <details>
                    <summary>Ar mokėjimai yra realūs?</summary>
                    <div class="body">Ši sistema yra demonstracinis projektas, todėl mokėjimų apdorojimas imituojamas.</div>
                </details>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="support-form-card">
                <h4 class="mb-3">Kontaktinė informacija</h4>
                <div class="mb-3">
                    <div class="small text-muted">El. paštas</div>
                    <a href="mailto:info@fitshop.lt" class="fw-semibold text-decoration-none">info@fitshop.lt</a>
                </div>
                <div class="mb-3">
                    <div class="small text-muted">Telefonas</div>
                    <a href="tel:+37060000000" class="fw-semibold text-decoration-none">+370 600 00000</a>
                </div>
                <div>
                    <div class="small text-muted">Adresas</div>
                    <div class="fw-semibold">Gedimino pr. 1, Vilnius</div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
