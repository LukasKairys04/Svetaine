@extends('layouts.app')
@section('title', 'Kalorijų planuotojas')

@section('content')
<section class="container my-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Pradžia</a></li>
            <li class="breadcrumb-item"><a href="{{ route('nutrition.index') }}" class="text-decoration-none text-muted">Mityba</a></li>
            <li class="breadcrumb-item active">Kalorijų planuotojas</li>
        </ol>
    </nav>

    <div class="mb-4">
        <div class="section-label">Asmeninis planas</div>
        <h1 class="fw-bold mb-1">Kalorijų ir makro planuotojas</h1>
        <p class="text-muted mb-0" style="max-width:760px">
            Skaičiavimai paremti recenzuotais 2022–2024 m. mokslinių tyrimų duomenimis
            (Mifflin-St Jeor formulė, Nunes ir kt. 2022 — baltymų poreikis,
            Roth ir kt. 2022 — saugus deficito greitis).
        </p>
    </div>

    <div class="planner-grid">
        {{-- Kairė: forma --}}
        <div class="planner-form">
            <h4>Tavo duomenys</h4>

            <div class="form-row">
                <div>
                    <label>Lytis</label>
                    <div class="segmented">
                        <input type="radio" name="sex" id="sex-m" value="m" checked>
                        <label for="sex-m">Vyras</label>
                        <input type="radio" name="sex" id="sex-f" value="f">
                        <label for="sex-f">Moteris</label>
                    </div>
                </div>
                <div>
                    <label for="p-age">Amžius</label>
                    <input type="number" id="p-age" min="14" max="90" value="28">
                </div>
            </div>

            <div class="form-row">
                <div>
                    <label for="p-height">Ūgis (cm)</label>
                    <input type="number" id="p-height" min="120" max="230" value="175">
                </div>
                <div>
                    <label for="p-weight">Svoris (kg)</label>
                    <input type="number" id="p-weight" min="35" max="250" step="0.1" value="75">
                </div>
            </div>

            <div class="form-row single">
                <div>
                    <label for="p-activity">
                        Fizinis aktyvumas
                        <span class="hint-icon" title="Aktyvumo koeficientai naudojami kaip praktinis standartas sporto mitybos planavime. Jei treniruojiesi 3–5 k./sav. ir turi sėdimą darbą — rinkis „Vidutinis“.">ⓘ</span>
                    </label>
                    <select id="p-activity">
                        <option value="1.2">Sėdimas (be sporto)</option>
                        <option value="1.375">Lengvas (1–3× / sav.)</option>
                        <option value="1.55" selected>Vidutinis (3–5× / sav.)</option>
                        <option value="1.725">Aktyvus (6–7× / sav.)</option>
                        <option value="1.9">Labai aktyvus (fizinis darbas + sportas)</option>
                    </select>
                </div>
            </div>

            <div class="form-row single">
                <div>
                    <label>Tikslas</label>
                    <div class="segmented">
                        <input type="radio" name="goal" id="g-cut"  value="cut">
                        <label for="g-cut">Mesti svorį</label>
                        <input type="radio" name="goal" id="g-maint" value="maintain" checked>
                        <label for="g-maint">Palaikyti</label>
                        <input type="radio" name="goal" id="g-bulk" value="bulk">
                        <label for="g-bulk">Auginti raumenis</label>
                    </div>
                </div>
            </div>

            {{-- Svorio metimo greitis (rodomas, kai pasirinktas tikslas „Mesti svorį“) --}}
            <div class="form-row single" id="rateRow-cut" style="display:none">
                <div>
                    <label for="p-cut-rate">
                        Svorio metimo greitis
                        <span class="hint-icon" title="Roth ir kt. 2022 meta-analizė: >1 % kūno svorio per savaitę reikšmingai didina raumenų masės nuostolius. Rekomenduojama 0,5–0,75 %/sav.">ⓘ</span>
                    </label>
                    <select id="p-cut-rate">
                        <option value="0.005">Lėtas — 0,5 % kūno svorio / sav. (rekomenduojama)</option>
                        <option value="0.0075" selected>Vidutinis — 0,75 % / sav.</option>
                        <option value="0.01">Agresyvus — 1 % / sav.</option>
                    </select>
                </div>
            </div>

            {{-- Masės auginimo greitis (rodomas, kai pasirinktas tikslas „Auginti raumenis“) --}}
            <div class="form-row single" id="rateRow-bulk" style="display:none">
                <div>
                    <label for="p-bulk-rate">
                        Kalorijų perteklius
                        <span class="hint-icon" title="Naujausi sporto mitybos tyrimai rodo, kad treniruotiems dažniausiai pakanka ~5–10 % virš palaikymo. >15 % dažnai didina riebalų kaupimąsi.">ⓘ</span>
                    </label>
                    <select id="p-bulk-rate">
                        <option value="1.05">Lean bulk — +5 % (treniruotiems)</option>
                        <option value="1.10" selected>Standartinis — +10 %</option>
                        <option value="1.15">Didesnis — +15 % (pradedantiesiems)</option>
                    </select>
                </div>
            </div>

            <div class="form-row single">
                <div>
                    <label for="p-protein">
                        Baltymų norma
                        <span class="hint-icon" title="Nunes ir kt. 2022 (Adv Nutr): <65 m. aktyviems — ≥1,6 g/kg. Didesnės naudos >2,2 g/kg nenustatyta. Vyresniems (≥65 m.) — 1,2–1,6 g/kg.">ⓘ</span>
                    </label>
                    <select id="p-protein">
                        <option value="1.6">1,6 g/kg (minimumas aktyviems)</option>
                        <option value="1.8" selected>1,8 g/kg (rekomenduojama)</option>
                        <option value="2.0">2,0 g/kg (dietos metu)</option>
                        <option value="2.2">2,2 g/kg (agresyvus deficitas / pažengusiems)</option>
                    </select>
                </div>
            </div>

            <div class="form-row single">
                <div>
                    <label for="p-diet">Dietos stilius (makro paskirstymas)</label>
                    <select id="p-diet">
                        <option value="">— Subalansuota —</option>
                        @foreach($plans as $p)
                            <option value="{{ $p->slug }}" data-desc="{{ $p->short_description }}" data-name="{{ $p->name }}" @selected($preselected === $p->slug)>
                                {{ $p->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Dešinė: rezultatai --}}
        <aside class="planner-result">
            <h4>Tavo dienos planas</h4>

            <div class="planner-diet-pick" id="dietPick" style="display:none">
                <div class="label">Pasirinkta dieta</div>
                <div class="name" id="dietName"></div>
                <div class="desc" id="dietDesc"></div>
            </div>

            <div class="planner-kcal">
                <span id="kcal">2 400</span><span class="unit">kcal</span>
            </div>
            <div class="planner-goal-label" id="goalLabel">Palaikymas · vidutinis aktyvumas</div>

            {{-- BMR / TDEE suvestinė --}}
            <div class="energy-breakdown">
                <div><span>BMR</span><strong id="e-bmr">1 700</strong> kcal</div>
                <div><span>TDEE</span><strong id="e-tdee">2 400</strong> kcal</div>
                <div><span>Tikslas</span><strong id="e-goal">+0</strong> kcal</div>
            </div>

            {{-- Makro juosta --}}
            <div class="macro-bar" aria-hidden="true">
                <div class="seg p" id="bar-p" style="width:30%"></div>
                <div class="seg c" id="bar-c" style="width:45%"></div>
                <div class="seg f" id="bar-f" style="width:25%"></div>
            </div>

            <div class="macros">
                <div class="macro-card">
                    <div class="macro-label">Baltymai</div>
                    <div class="macro-value"><span id="m-p">135</span><span class="g">g</span></div>
                    <div class="macro-kcal" id="k-p">540 kcal</div>
                </div>
                <div class="macro-card">
                    <div class="macro-label">Angliav.</div>
                    <div class="macro-value"><span id="m-c">270</span><span class="g">g</span></div>
                    <div class="macro-kcal" id="k-c">1 080 kcal</div>
                </div>
                <div class="macro-card">
                    <div class="macro-label">Riebalai</div>
                    <div class="macro-value"><span id="m-f">80</span><span class="g">g</span></div>
                    <div class="macro-kcal" id="k-f">720 kcal</div>
                </div>
            </div>

            {{-- Vandens norma --}}
            <div class="water-card">
                <div class="water-icon"><i class="bi bi-droplet-fill"></i></div>
                <div>
                    <div class="water-label">Rekomenduojamas vanduo per dieną</div>
                    <div class="water-value"><span id="m-water">2,6</span> l</div>
                    <div class="water-hint">35 ml/kg kūno svorio + 500 ml už kiekvieną treniruotę</div>
                </div>
            </div>

            {{-- Įspėjimai --}}
            <div id="warnings" class="planner-warnings"></div>

            <p class="planner-note">
                Tai atspirties taškas. Reguliuok kas 2–3 sav. pagal realų svorio ir savijautos pokytį.
                Moksliniai šaltiniai — puslapio apačioje.
            </p>
        </aside>
    </div>

    {{-- Mokslinių šaltinių sąrašas --}}
    <div class="citations-block mt-5">
        <div class="section-label">Moksliniai šaltiniai</div>
        <h3 class="mb-3">Kuo remiamės</h3>
        <div class="evidence-grid">
            <a class="evidence-item" href="https://pmc.ncbi.nlm.nih.gov/articles/PMC8978023/" target="_blank" rel="noopener">
                <h6>Baltymų norma aktyviems</h6>
                <p>Aktyviai sportuojantiems efektyvi baltymų norma dažniausiai prasideda nuo 1,6 g/kg.</p>
                <span>Nunes et al., 2022</span>
            </a>
            <a class="evidence-item" href="https://pmc.ncbi.nlm.nih.gov/articles/PMC9012799/" target="_blank" rel="noopener">
                <h6>Deficitas ir raumenų išsaugojimas</h6>
                <p>Per greitas kalorijų mažinimas didina liesos masės nuostolių riziką.</p>
                <span>Roth et al., 2022</span>
            </a>
            <a class="evidence-item" href="https://pubmed.ncbi.nlm.nih.gov/34623696/" target="_blank" rel="noopener">
                <h6>Energijos trūkumas</h6>
                <p>Didelis energijos deficitas blogina kūno kompozicijos progresą net treniruojantis.</p>
                <span>Murphy &amp; Koehler, 2021</span>
            </a>
            <a class="evidence-item" href="https://pubmed.ncbi.nlm.nih.gov/36169010/" target="_blank" rel="noopener">
                <h6>Skysčių poreikiai sportuojant</h6>
                <p>Skysčių poreikį reikia koreguoti pagal kūno masę, prakaitavimą ir fizinį krūvį.</p>
                <span>Perrier et al., 2022</span>
            </a>
            <a class="evidence-item" href="https://pubmed.ncbi.nlm.nih.gov/37530886/" target="_blank" rel="noopener">
                <h6>Metaanalizės apie kūno kompoziciją</h6>
                <p>Kūno kompozicijai svarbiausia nuoseklus kalorijų valdymas ir pakankamas baltymų kiekis.</p>
                <span>Ashtary-Larky et al., 2023</span>
            </a>
        </div>
    </div>
</section>

@push('scripts')
<script>
/**
 * Mokslinis kalorijų ir makronutrientų planuotojas.
 *
 * Skaičiavimų logika (visi žingsniai paremti 2020+ publikacijomis):
 *   1) BMR — praktikoje plačiai taikoma formulė.
 *   2) TDEE = BMR × PAL (FAO/WHO).
 *   3) Tikslas:
 *      - Mesti: procentas kūno svorio / sav. → ~7700 kcal / kg riebalų → dienos deficitas.
 *        Ribos 0,5–1 %/sav. pagrįstos Roth 2022 ir Murphy & Koehler 2021 (LBM apsauga).
 *      - Palaikyti: TDEE.
 *      - Auginti: +5/+10/+15 % perteklius (2020+ sporto mitybos rekomendacijų logika).
 *   4) Baltymai: g/kg × kūno svoris (Nunes 2022 — ≥1,6 g/kg aktyviems).
 *   5) Riebalai: min. 0,8 g/kg (hormonų palaikymui), bet nesikartoja su dietos dalimi.
 *   6) Angliavandeniai: likusios kalorijos.
 *   7) Vanduo: 35 ml/kg + treniruotės korekcija.
 *
 * Forma saugoma localStorage — jokių serverio iškvietimų.
 */
(function () {
    const STORAGE_KEY = 'fitshop.planner.v2';
    const KCAL_PER_KG_FAT = 7700;  // Plačiai priimta aproksimacija svorio pokyčiui skaičiuoti.

    const $ = id => document.getElementById(id);

    // Dietų stilių makro paskirstymai naudojami tik angliavandenių/riebalų santykiui
    // po to, kai baltymai jau apskaičiuoti pagal kūno svorį. Procentai — % iš likusių kalorijų.
    const DIET_CARB_FAT_RATIOS = {
        'low-fat':       { c: 75, f: 25 }, // mažai riebalų
        'vegetarian':    { c: 65, f: 35 },
        'mediterranean': { c: 60, f: 40 },
        'carnivore':     { c:  5, f: 95 }, // keto-artima
        '':              { c: 65, f: 35 }, // subalansuota (numatytoji)
    };

    const GOAL_LABELS = { cut: 'Svorio metimas', maintain: 'Palaikymas', bulk: 'Raumenų auginimas' };

    function readForm() {
        return {
            sex: document.querySelector('input[name="sex"]:checked')?.value || 'm',
            age: parseInt($('p-age').value) || 28,
            height: parseFloat($('p-height').value) || 175,
            weight: parseFloat($('p-weight').value) || 75,
            activity: parseFloat($('p-activity').value) || 1.55,
            goal: document.querySelector('input[name="goal"]:checked')?.value || 'maintain',
            cutRate: parseFloat($('p-cut-rate').value) || 0.0075,
            bulkRate: parseFloat($('p-bulk-rate').value) || 1.10,
            protein: parseFloat($('p-protein').value) || 1.8,
            diet: $('p-diet').value || '',
        };
    }

    const save = v => { try { localStorage.setItem(STORAGE_KEY, JSON.stringify(v)); } catch (e) {} };
    const load = () => { try { return JSON.parse(localStorage.getItem(STORAGE_KEY)); } catch (e) { return null; } };

    const format = n => new Intl.NumberFormat('lt-LT').format(Math.round(n));
    const formatDec = (n, d = 1) => new Intl.NumberFormat('lt-LT', { maximumFractionDigits: d, minimumFractionDigits: d }).format(n);

    function toggleRateRows(goal) {
        $('rateRow-cut').style.display  = goal === 'cut'  ? '' : 'none';
        $('rateRow-bulk').style.display = goal === 'bulk' ? '' : 'none';
    }

    function calculate() {
        const v = readForm();
        save(v);
        toggleRateRows(v.goal);

        // 1) BMR — Mifflin-St Jeor
        const bmr = v.sex === 'm'
            ? 10 * v.weight + 6.25 * v.height - 5 * v.age + 5
            : 10 * v.weight + 6.25 * v.height - 5 * v.age - 161;

        // 2) TDEE
        const tdee = bmr * v.activity;

        // 3) Tikslo kalorijos
        let kcal = tdee;
        let goalDelta = 0;
        if (v.goal === 'cut') {
            // Savaitinis svorio metimas → dienos deficitas.
            const weeklyKgLoss = v.weight * v.cutRate;
            goalDelta = -Math.round((weeklyKgLoss * KCAL_PER_KG_FAT) / 7);
            kcal = tdee + goalDelta;
        } else if (v.goal === 'bulk') {
            kcal = tdee * v.bulkRate;
            goalDelta = Math.round(kcal - tdee);
        }
        kcal = Math.max(1200, Math.round(kcal)); // Saugumo slenkstis — po 1200 kcal niekada neleisti.

        // 4) Baltymai pagal kūno svorį (Nunes 2022).
        const proteinG = Math.round(v.weight * v.protein);
        const proteinKcal = proteinG * 4;

        // 5) Riebalai: minimumas 0,8 g/kg; po to — pagal dietos stiliaus proporciją.
        const minFatG = Math.round(v.weight * 0.8);
        const minFatKcal = minFatG * 9;

        const remainingKcal = Math.max(0, kcal - proteinKcal);
        const split = DIET_CARB_FAT_RATIOS[v.diet] ?? DIET_CARB_FAT_RATIOS[''];
        let fatKcal = remainingKcal * (split.f / 100);
        if (fatKcal < minFatKcal) fatKcal = Math.min(remainingKcal, minFatKcal); // užtikrinam hormonų palaikymą
        const carbsKcal = Math.max(0, remainingKcal - fatKcal);
        const fatG = Math.round(fatKcal / 9);
        const carbsG = Math.round(carbsKcal / 4);

        // 6) Vanduo: 35 ml/kg bazinis, +500 ml kiekvienai treniruotei pagal aktyvumo lygį.
        const trainingSessions = { 1.2: 0, 1.375: 2, 1.55: 4, 1.725: 6, 1.9: 7 };
        const extraWater = (trainingSessions[v.activity] ?? 4) * 500 / 7; // vidutiniškai per dieną
        const waterLiters = (v.weight * 35 + extraWater) / 1000;

        // --- DOM atnaujinimas ---
        $('kcal').textContent = format(kcal);
        $('e-bmr').textContent = format(bmr);
        $('e-tdee').textContent = format(tdee);
        $('e-goal').textContent = (goalDelta >= 0 ? '+' : '') + format(goalDelta);
        $('m-p').textContent = proteinG;
        $('m-c').textContent = carbsG;
        $('m-f').textContent = fatG;
        $('k-p').textContent = format(proteinKcal) + ' kcal';
        $('k-c').textContent = format(carbsKcal) + ' kcal';
        $('k-f').textContent = format(fatKcal) + ' kcal';
        $('m-water').textContent = formatDec(waterLiters);

        const total = proteinKcal + carbsKcal + fatKcal || 1;
        $('bar-p').style.width = (proteinKcal / total * 100) + '%';
        $('bar-c').style.width = (carbsKcal   / total * 100) + '%';
        $('bar-f').style.width = (fatKcal     / total * 100) + '%';

        const activityLabel = $('p-activity').selectedOptions[0]?.text.toLowerCase() || '';
        $('goalLabel').textContent = `${GOAL_LABELS[v.goal]} · ${activityLabel}`;

        const dietOpt = $('p-diet').selectedOptions[0];
        const dietPick = $('dietPick');
        if (v.diet && dietOpt) {
            dietPick.style.display = '';
            $('dietName').textContent = dietOpt.dataset.name || dietOpt.text;
            $('dietDesc').textContent = dietOpt.dataset.desc || '';
        } else {
            dietPick.style.display = 'none';
        }

        // --- Įspėjimai ---
        const warnings = [];
        if (v.goal === 'cut' && v.cutRate >= 0.01) {
            warnings.push('Agresyvus deficitas (1 %/sav.) padidina raumenų masės nuostolius. Svarbu laikytis ≥1,6 g/kg baltymų ir reguliariai treniruotis su svoriais (Roth 2022).');
        }
        if (kcal < bmr) {
            warnings.push(`Planuojamos kalorijos (${format(kcal)}) yra mažesnės nei tavo BMR (${format(bmr)}). Tai gali sulėtinti medžiagų apykaitą ir sukelti energijos trūkumą.`);
        }
        if (v.protein < 1.6 && v.activity >= 1.55) {
            warnings.push('Esant aktyviai treniruotei, rekomenduojama bent 1,6 g/kg baltymų (Nunes 2022).');
        }
        if (v.age >= 65 && v.protein < 1.2) {
            warnings.push('Vyresnio amžiaus asmenims (≥65 m.) rekomenduojama bent 1,2 g/kg baltymų dėl sarkopenijos prevencijos.');
        }

        const wrap = $('warnings');
        if (warnings.length) {
            wrap.innerHTML = warnings.map(w => `<div class="warning-item"><i class="bi bi-exclamation-triangle-fill"></i><span>${w}</span></div>`).join('');
        } else {
            wrap.innerHTML = '';
        }
    }

    // Atkuriam ankstesnius duomenis.
    const saved = load();
    if (saved) {
        if (saved.sex)      { const el = document.querySelector(`input[name="sex"][value="${saved.sex}"]`); if (el) el.checked = true; }
        if (saved.age)      $('p-age').value = saved.age;
        if (saved.height)   $('p-height').value = saved.height;
        if (saved.weight)   $('p-weight').value = saved.weight;
        if (saved.activity) $('p-activity').value = saved.activity;
        if (saved.goal)     { const el = document.querySelector(`input[name="goal"][value="${saved.goal}"]`); if (el) el.checked = true; }
        if (saved.cutRate)  $('p-cut-rate').value = saved.cutRate;
        if (saved.bulkRate) $('p-bulk-rate').value = saved.bulkRate;
        if (saved.protein)  $('p-protein').value = saved.protein;
        if (saved.diet && !$('p-diet').value) $('p-diet').value = saved.diet;
    }

    // Event'ai
    ['p-age','p-height','p-weight','p-activity','p-cut-rate','p-bulk-rate','p-protein','p-diet']
        .forEach(id => $(id) && $(id).addEventListener('input', calculate));
    document.querySelectorAll('input[type="radio"]').forEach(el => el.addEventListener('change', calculate));

    calculate();
})();
</script>
@endpush
@endsection
