@extends('layouts.app')

@section('title', 'Mityba')



@section('content')

<section class="container my-4">



    {{-- Hero: dark background with food image overlay --}}

    <div class="nutrition-hero">

        <div>

            <div class="eyebrow">Mityba</div>

            <h1>Mityba — pagrindas viskam, ką darai</h1>

            <p>Pasirink sau tinkamą mitybos stilių, apskaičiuok BMI ir susikurk asmeninį kalorijų planą.</p>

        </div>

    </div>



    {{-- Why nutrition matters — 4 short benefit points --}}

    <div class="section-block" style="margin-top:0">

        <div class="section-label">Kodėl tai svarbu</div>

        <h3>Ką mityba gali padėti pasiekti</h3>

        <p class="text-muted" style="max-width:720px">Geresni rezultatai sporte prasideda nuo mitybos. Tinkamai suderintas maistas padeda auginti raumenis, mesti svorį, gerina savijautą ir miegą. Tai nereiškia, kad reikia griežtai skaičiuoti kiekvieną gramą — svarbiausia suprasti pagrindus.</p>



        <div class="benefits-grid">

            <div class="benefit-item">

                <div class="num">01</div>

                <h6>Energija dienai</h6>

                <p>Kokybiški angliavandeniai ir riebalai suteikia ilgalaikę energiją — be svyravimų.</p>

            </div>

            <div class="benefit-item">

                <div class="num">02</div>

                <h6>Raumenų augimas</h6>

                <p>Pakankamas baltymų kiekis (1.6–2.2 g/kg) yra būtinas atsistatymui ir progresui.</p>

            </div>

            <div class="benefit-item">

                <div class="num">03</div>

                <h6>Svorio kontrolė</h6>

                <p>Kalorijų deficitas ar perteklius lemia svorio kryptį — pasirenkamas su tikslu.</p>

            </div>

            <div class="benefit-item">

                <div class="num">04</div>

                <h6>Savijauta ir miegas</h6>

                <p>Mikroelementai, skaidulinės medžiagos ir vanduo tiesiogiai veikia miegą ir nuotaiką.</p>

            </div>

        </div>

    </div>



    {{-- Diet types — 4 cards with pros/cons --}}

    <div class="section-block">

        <div class="diet-grid">

            @foreach($plans as $plan)

                <div class="diet-card">

                    @if($plan->image)

                        <div class="cover" style="background-image:url('{{ $plan->image }}')">

                            <div class="cover-title">{{ $plan->name }}</div>

                        </div>

                    @else

                        <div class="cover-title-plain">{{ $plan->name }}</div>

                    @endif

                    <div class="body">

                        <p class="short">{{ $plan->short_description }}</p>



                        <div class="proscons">

                            <div class="pc-col pros">

                                <h6>Pliusai</h6>

                                <ul>

                                    @foreach(($plan->pros ?? []) as $pro)

                                        <li>{{ $pro }}</li>

                                    @endforeach

                                </ul>

                            </div>

                            <div class="pc-col cons">

                                <h6>Minusai</h6>

                                <ul>

                                    @foreach(($plan->cons ?? []) as $con)

                                        <li>{{ $con }}</li>

                                    @endforeach

                                </ul>

                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>



    {{-- BMI calculator — fully client-side via Alpine-style JS --}}

    <div class="section-block">

        <div class="section-label">Įrankis</div>

        <h3>BMI skaičiuoklė</h3>



        <div class="bmi-panel">

            <div>

                <h3 class="h5">Apskaičiuok savo kūno masės indeksą</h3>

                <p class="lead">BMI rodo, ar tavo svoris yra sveikoje normoje pagal ūgį. Tai greitas būdas pasimatuoti — bet nevertink perdaug, nes BMI nekuria skirtumo tarp raumenų ir riebalų.</p>



                <div class="bmi-inputs">

                    <div>

                        <label for="bmi-height">Ūgis (cm)</label>

                        <input type="number" id="bmi-height" min="100" max="250" step="0.5" value="175" inputmode="decimal">

                    </div>

                    <div>

                        <label for="bmi-weight">Svoris (kg)</label>

                        <input type="number" id="bmi-weight" min="30" max="300" step="0.1" value="70" inputmode="decimal">

                    </div>

                </div>



                <p class="bmi-note">

                    <strong>Kaip tai skaičiuojama?</strong>

                    BMI = svoris (kg) / ūgis (m)². Pavyzdžiui, 70 kg ir 1.75 m ūgio žmogaus BMI = 22.9 — tai normali riba.

                </p>

            </div>



            <div class="bmi-result">

                <div class="bmi-value" id="bmi-value">22.9</div>

                <div class="bmi-category bmi-cat-normal" id="bmi-category">Normalus svoris</div>



                {{-- BMI skalė. Skaitiniai žymekliai (18,5 · 25 · 30) pozicionuoti absoliučiai

                     ta pačia formule kaip ir markeris — BMI [10..40] → [0..100 %] juostos plotyje. --}}

                <div class="bmi-scale">

                    <div class="bmi-marker" id="bmi-marker" style="left:45%"></div>

                </div>

                <div class="bmi-scale-labels">

                    <span style="left:0%">10</span>

                    <span style="left:28.33%">18,5</span>

                    <span style="left:50%">25</span>

                    <span style="left:66.67%">30</span>

                    <span style="left:100%">40+</span>

                </div>



                <div>

                    <label for="dietStyle" class="small text-muted">Dietos stilius</label>

                    <select id="dietStyle" class="form-select">

                        <option value="" selected></option>

                        <option value="balanced">Subalansuota</option>

                        <option value="low-carb">Mažai angliavandenių</option>

                        <option value="high-protein">Daug baltymų</option>

                        <option value="keto">Keto</option>

                    </select>

                </div>



                <p class="bmi-note" id="bmi-suggestion">Tavo svoris yra sveikoje normoje. Koncentruokis į gerą mitybą ir reguliarų sportą.</p>

            </div>

        </div>

    </div>



    {{-- CTA to full calorie planner --}}

    <div class="cta-builder">

        <div>

            <h4>Nori išsamesnio plano?</h4>

            <p>Susikurk asmeninį kalorijų ir makro planą pagal savo tikslą ir pasirinktą dietą.</p>

        </div>

        <a href="{{ route('nutrition.planner') }}" class="btn-cta">

            <i class="bi bi-clipboard-data"></i> Kalorijų planuotojas

        </a>

    </div>

</section>



@push('scripts')

<script>

/**

 * Inline BMI calculator.

 * Recalculates on every input change, updates value, category badge,

 * marker position on the color scale, and a short suggestion string.

 */

(function () {

    const heightEl = document.getElementById('bmi-height');

    const weightEl = document.getElementById('bmi-weight');

    const valueEl = document.getElementById('bmi-value');

    const catEl = document.getElementById('bmi-category');

    const markerEl = document.getElementById('bmi-marker');

    const sugEl = document.getElementById('bmi-suggestion');



    // Categorize BMI value and return display info.

    function classify(bmi) {

        if (bmi < 18.5) return { label: 'Per mažas svoris', cls: 'bmi-cat-under', tip: 'Gali trūkti kalorijų ir mikroelementų. Pasikonsultuok su gydytoju ir pagalvok apie kalorijų pertekliaus planą.' };

        if (bmi < 25)   return { label: 'Normalus svoris', cls: 'bmi-cat-normal', tip: 'Tavo svoris yra sveikoje normoje. Koncentruokis į kokybišką mitybą ir reguliarų sportą.' };

        if (bmi < 30)   return { label: 'Antsvoris',        cls: 'bmi-cat-over', tip: 'Nedidelis kalorijų deficitas (−300–500 kcal) ir sporto pridėjimas gali padėti pasiekti sveikesnę ribą.' };

        return            { label: 'Nutukimas',            cls: 'bmi-cat-obese', tip: 'Rekomenduojama konsultacija su specialistu. Pradėk nuo mažų kasdienių pakeitimų — judėjimas, geresnis maistas, miegas.' };

    }



    // Convert BMI to a 0–100% position on the color scale (10..40 clamp).

    function markerPosition(bmi) {

        const clamped = Math.max(10, Math.min(40, bmi));

        return ((clamped - 10) / (40 - 10)) * 100;

    }



    function update() {

        const h = parseFloat(heightEl.value);

        const w = parseFloat(weightEl.value);

        if (!h || !w || h <= 0) { valueEl.textContent = '—'; return; }

        const bmi = w / Math.pow(h / 100, 2);

        valueEl.textContent = bmi.toFixed(1);

        const c = classify(bmi);

        catEl.textContent = c.label;

        catEl.className = 'bmi-category ' + c.cls;

        markerEl.style.left = markerPosition(bmi) + '%';

        sugEl.textContent = c.tip;

    }



    [heightEl, weightEl].forEach(el => el.addEventListener('input', update));

    update();

})();

</script>

@endpush

@endsection

