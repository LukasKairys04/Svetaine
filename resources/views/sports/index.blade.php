@extends('layouts.app')

@section('title', 'Sportas')



@section('content')

<section class="container my-4">

    {{-- Hero --}}

    <div class="sports-hero mb-5">

        <div>

            <div class="eyebrow">Treniruočių centras</div>

            <h1>Sportas, kuris atitinka tavo tikslus</h1>

            <p>Pasirink sporto šaką, peržiūrėk paruoštus planus arba susikurk savo treniruočių sistemą per kelias minutes.</p>

        </div>

    </div>



    @if(($featuredPlans ?? collect())->isNotEmpty())

        <div class="section-block">

            <div class="section-label">Programos</div>

            <h3>Jau sukurti planai</h3>

            <div class="row g-3 mb-2">

                @foreach($featuredPlans as $plan)

                    <div class="col-md-4">

                        <article class="card-clean plan-card compact-plan-card">

                            <div class="meta">

                                <span class="meta-pill">{{ $plan->sport?->name ?? 'Sportas' }}</span>

                                <span class="meta-pill level">{{ ucfirst($plan->level) }}</span>

                            </div>

                            <h5>{{ $plan->name }}</h5>

                            <p>{{ Str::limit(strip_tags((string) $plan->description), 120) }}</p>

                            <a href="{{ route('sports.plan', $plan->slug) }}" class="plan-link">Peržiūrėti planą <i class="bi bi-arrow-right"></i></a>

                        </article>

                    </div>

                @endforeach

            </div>

        </div>

    @endif



    {{-- Workout splits --}}

    <div class="section-block">

        <div class="section-label">Sistemos</div>

        <h3>Treniruočių struktūros</h3>

        <p class="text-muted mb-4" style="max-width:640px">Pasirink struktūrą, pagal kurią nori suskirstyti treniruotes per savaitę. Keičiant splitą ir dienų skaičių, kalendorius apačioje atsinaujina automatiškai.</p>



        <div class="split-grid mb-4">

            @php

                $splits = [

                    ['code' => 'fb', 'name' => 'Full Body', 'freq' => '2–4×/sav.', 'desc' => 'Visas kūnas kiekvienoj treniruotėj. Idealu pradedantiesiems.', 'days' => ['FB A', 'FB B', 'FB C']],

                    ['code' => 'ul', 'name' => 'Upper / Lower', 'freq' => '4×/sav.', 'desc' => 'Viršutinis kūnas / apatinis kūnas. Balansas ir atsistatymas.', 'days' => ['Upper', 'Lower', 'Upper', 'Lower']],

                    ['code' => 'ppl', 'name' => 'Push / Pull / Legs', 'freq' => '5–6×/sav.', 'desc' => 'Stūmimas, traukimas, kojos. Populiariausias splitas pažengusiems.', 'days' => ['Push', 'Pull', 'Legs', 'Push', 'Pull', 'Legs']],

                    ['code' => 'broman', 'name' => 'Raumenų grupių dienos', 'freq' => '5×/sav.', 'desc' => 'Kiekvienai raumenų grupei skiriama atskira diena su didesniu darbiniu tūriu.', 'days' => ['Krūtinė', 'Nugara', 'Pečiai', 'Rankos', 'Kojos']],

                    ['code' => 'phul', 'name' => 'PHUL (jėga + hipertrofija)', 'freq' => '4×/sav.', 'desc' => '2 sunkesnės jėgos dienos ir 2 hipertrofijos dienos su didesniu tūriu.', 'days' => ['Upper Power', 'Lower Power', 'Upper Hypertrophy', 'Lower Hypertrophy']],

                    ['code' => 'hybrid', 'name' => 'Hybrid (jėga + kardio)', 'freq' => '4–5×/sav.', 'desc' => 'Jėgos treniruotės derinamos su ištvermės sesijomis vienoje savaitėje.', 'days' => ['Jėga A', 'Kardio', 'Jėga B', 'Intervalai', 'Mobility']],

                ];

            @endphp

            @foreach($splits as $split)

                <div class="split-card-v2" data-split="{{ $split['code'] }}">

                    <span class="tag">{{ $split['freq'] }}</span>

                    <h5>{{ $split['name'] }}</h5>

                    <p>{{ $split['desc'] }}</p>

                    <div class="days-preview">

                        @foreach($split['days'] as $d)

                            <span>{{ $d }}</span>

                        @endforeach

                    </div>

                </div>

            @endforeach

        </div>



    </div>



    {{-- Days picker --}}

    <div class="days-picker-v2 mb-4">

        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">

            <div>

                <div class="section-label">Kalendorius</div>

                <h3 class="m-0">Kiek dienų per savaitę gali sportuoti?</h3>

            </div>

            <div class="days-selector">

                @for($d = 2; $d <= 6; $d++)

                    <button type="button" class="{{ $d === 4 ? 'active' : '' }}" data-days="{{ $d }}">{{ $d }}</button>

                @endfor

            </div>

        </div>



        <div class="week-grid">

            @foreach(['Pir', 'Ant', 'Tre', 'Ket', 'Pen', 'Šeš', 'Sek'] as $i => $day)

                <div class="week-day rest" data-day="{{ $i }}">

                    <div class="dow">{{ $day }}</div>

                    <div class="wk">Poilsis</div>

                </div>

            @endforeach

        </div>

    </div>



    {{-- CTA Builder --}}

    <div class="cta-builder">

        <div>

            <h4>Nori pilnos kontrolės?</h4>

            <p>Susikurk savo planą — pridėk pratimus, nustatyk sets, reps, svorį ir RIR.</p>

        </div>

        <a href="{{ route('sports.builder') }}" class="btn-cta">

            <i class="bi bi-tools"></i> Susikurk savo planą

        </a>

    </div>

</section>



@push('scripts')

<script>

// Split selection

document.querySelectorAll('.split-card-v2').forEach(c => c.addEventListener('click', () => {

    document.querySelectorAll('.split-card-v2').forEach(x => x.classList.remove('selected'));

    c.classList.add('selected');

    updateSchedule();

}));



// Days picker

document.querySelectorAll('.days-selector button').forEach(b => b.addEventListener('click', () => {

    document.querySelectorAll('.days-selector button').forEach(x => x.classList.remove('active'));

    b.classList.add('active');

    updateSchedule();

}));



const splitTemplates = {

    fb: { 2: ['FB A','FB B'], 3: ['FB A','FB B','FB C'], 4: ['FB A','FB B','FB C','FB D'], 5: ['FB A','FB B','FB C','FB D','FB E'], 6: ['FB A','FB B','FB C','FB D','FB E','FB F'] },

    ul: { 2: ['Upper','Lower'], 3: ['Upper','Lower','Upper'], 4: ['Upper','Lower','Upper','Lower'], 5: ['Upper','Lower','Upper','Lower','FB'], 6: ['Upper','Lower','Upper','Lower','Upper','Lower'] },

    ppl: { 3: ['Push','Pull','Legs'], 4: ['Push','Pull','Legs','Upper'], 5: ['Push','Pull','Legs','Upper','Lower'], 6: ['Push','Pull','Legs','Push','Pull','Legs'] },

    broman: { 4: ['Krūtinė','Nugara','Pečiai','Kojos'], 5: ['Krūtinė','Nugara','Pečiai','Rankos','Kojos'], 6: ['Krūtinė','Nugara','Pečiai','Rankos','Kojos','Core'] },

    phul: { 4: ['Upper Power','Lower Power','Upper Hypertrophy','Lower Hypertrophy'], 5: ['Upper Power','Lower Power','Poilsis','Upper Hypertrophy','Lower Hypertrophy'] },

    hybrid: { 4: ['Jėga A','Kardio','Jėga B','Mobility'], 5: ['Jėga A','Kardio','Jėga B','Intervalai','Mobility'], 6: ['Jėga A','Kardio','Jėga B','Intervalai','Jėga C','Mobility'] }

};



function updateSchedule() {

    const splitEl = document.querySelector('.split-card-v2.selected');

    const daysEl = document.querySelector('.days-selector button.active');

    if (!splitEl || !daysEl) return;

    const split = splitEl.dataset.split;

    const days = parseInt(daysEl.dataset.days);

    const available = Object.keys(splitTemplates[split] || {}).map(Number);

    const bestKey = available.includes(days) ? days : (available.length ? available.reduce((a, b) => Math.abs(b - days) < Math.abs(a - days) ? b : a) : days);

    const tpl = (splitTemplates[split] || {})[bestKey] || [];

    const schedule = new Array(7).fill('Poilsis');

    const positions = distributeDays(days);

    tpl.forEach((w, i) => { if (positions[i] !== undefined) schedule[positions[i]] = w; });

    document.querySelectorAll('.week-day').forEach((el, i) => {

        const isRest = schedule[i] === 'Poilsis';

        el.classList.toggle('workout', !isRest);

        el.classList.toggle('rest', isRest);

        el.querySelector('.wk').textContent = schedule[i];

    });

}



function distributeDays(count) {

    const maps = { 2: [0, 3], 3: [0, 2, 4], 4: [0, 1, 3, 4], 5: [0, 1, 2, 4, 5], 6: [0, 1, 2, 3, 4, 5] };

    return maps[count] || [];

}



document.querySelector('.split-card-v2')?.classList.add('selected');

updateSchedule();

</script>

@endpush

@endsection

