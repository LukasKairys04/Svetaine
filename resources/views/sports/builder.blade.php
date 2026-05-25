@extends('layouts.app')

@section('title', 'Plano kūrėjas')



@section('content')

<section class="container my-4 builder-wrapper">

    <div class="builder-header">

        <div>

            <div class="section-label">Plano kūrėjas</div>

            <h1>Susikurk savo treniruočių planą</h1>

            <p>Pasirink dienų skaičių, pridėk pratimus iš bibliotekos, suderink sets, reps, svorį ir RIR.</p>

        </div>

        <div class="d-flex gap-2 flex-wrap">

            <button class="btn btn-outline-secondary btn-sm" id="exportBtn"><i class="bi bi-download"></i> JSON</button>

            <button class="btn btn-outline-secondary btn-sm" id="printBtn"><i class="bi bi-printer"></i> Spausdinti</button>

            <button class="btn btn-outline-danger btn-sm" id="resetBtn"><i class="bi bi-arrow-counterclockwise"></i> Išvalyti</button>

        </div>

    </div>



    <div class="row g-4">
<div class="col-lg-4">

            <div class="panel">

                <div class="panel-header">

                    <h5><i class="bi bi-grid-3x3-gap"></i> Pratimai</h5>

                    <span class="text-muted small">{{ $exercises->count() }} variacijų</span>

                </div>

                <div class="panel-body lib-search">

                    <input type="text" id="exerciseSearch" class="form-control mb-2" placeholder="Ieškoti pratimo...">

                    <select id="muscleFilter" class="form-select">

                        <option value="">Visos raumenų grupės</option>

                        @foreach($muscleGroups as $mg)

                            <option value="{{ $mg }}">{{ $mg }}</option>

                        @endforeach

                    </select>

                </div>

                <div class="lib-list">

                    @foreach($exercises as $ex)

                        <div class="lib-item"

                             data-name="{{ $ex->name }}"

                             data-muscles="{{ Str::lower(implode(',', $ex->muscle_groups ?? [])) }}"

                             data-search="{{ Str::lower($ex->name . ' ' . implode(' ', $ex->muscle_groups ?? [])) }}">

                            <div class="flex-grow-1">

                                <div class="name">{{ $ex->name }}</div>

                                <div class="muscles">

                                    @foreach(array_slice($ex->muscle_groups ?? [], 0, 3) as $m)

                                        <span class="mus">{{ $m }}</span>

                                    @endforeach

                                </div>

                            </div>

                            <button type="button" class="lib-add add-exercise-btn" title="Pridėti">

                                <i class="bi bi-plus-lg"></i>

                            </button>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>
<div class="col-lg-8">

            <div class="panel">

                <div class="panel-header">

                    <div class="d-flex align-items-center gap-3 flex-wrap">

                        <h5 class="m-0">Tavo planas</h5>

                        <div class="d-flex align-items-center gap-2">

                            <span class="text-muted small">Dienos:</span>

                            <div class="days-selector" id="dayCountPicker" style="gap:.35rem">

                                @for($i = 2; $i <= 6; $i++)

                                    <button type="button" data-count="{{ $i }}" style="width:38px;height:38px;font-size:.9rem;border-radius:8px">{{ $i }}</button>

                                @endfor

                            </div>

                        </div>

                    </div>

                    <div class="small text-muted">

                        <strong id="totalExercises">0</strong> pratimai · <strong id="totalSets">0</strong> sets

                    </div>

                </div>

                <div class="day-tabs" id="dayTabs"></div>

                <div class="panel-body" id="dayContent"></div>

            </div>

        </div>

    </div>



    <div class="row g-3 mt-2 builder-info-row">
        <div class="col-lg-6">
            <div class="section-block m-0 builder-info-block h-100">
                <div class="section-label">Instrukcija</div>
                <h3>Kaip sukurti planą</h3>
                <div class="builder-steps one-col">
                    <div class="step-item"><span>1</span><p>Pasirink, kiek dienų per savaitę treniruosiesi.</p></div>
                    <div class="step-item"><span>2</span><p>Iš kairės pridėk pratimus į aktyvią dieną.</p></div>
                    <div class="step-item"><span>3</span><p>Kiekvienam pratimui nustatyk serijas, kartojimus, svorį ir poilsį.</p></div>
                </div>
                <p class="mb-0 builder-note">Pradžiai laikykis paprastai: 3–4 serijos, 6–12 kartojimų, 60–120 s poilsio. Svorį didink palaipsniui, kai paskutinės serijos atliekamos tvarkinga technika.</p>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="section-block m-0 builder-info-block h-100">
                <div class="section-label">Paaiškinimai</div>
                <h3>Pagrindinės sąvokos</h3>
                <div class="glossary-grid two-col-tight">
                    <div class="glossary-item"><strong>Sets</strong><span>Serijų skaičius vienam pratimui. Didesnis serijų kiekis didina savaitinį darbo tūrį ir padeda progresui.</span></div>
                    <div class="glossary-item"><strong>Reps</strong><span>Kartojimų skaičius vienoje serijoje. Mažiau kartojimų dažniau akcentuoja jėgą, daugiau — ištvermę ir tūrį.</span></div>
                    <div class="glossary-item"><strong>RIR</strong><span>Kiek kartojimų dar liktų rezerve po serijos. Tai padeda valdyti nuovargį ir treniruotis saugiau.</span></div>
                    <div class="glossary-item"><strong>Poilsis</strong><span>Laikas tarp serijų sekundėmis. Trumpesnis poilsis kelia intensyvumą, ilgesnis leidžia atkurti jėgą kitai serijai.</span></div>
                </div>
            </div>
        </div>
    </div>
<div class="citations-block mt-3 builder-info-centered">

        <div class="section-label">Moksliniai šaltiniai</div>

        <h3 class="mb-3">Kuo remiamės</h3>

        <div class="evidence-grid">

            <a class="evidence-item" href="https://www.frontiersin.org/journals/sports-and-active-living/articles/10.3389/fspor.2024.1429789/full" target="_blank" rel="noopener">

                <h6>Poilsio trukmė tarp serijų</h6>

                <p>Raumenų augimui poilsio trukmė daro realią įtaką progresui, todėl plane ją sekame atskirai.</p>

                <span>Androulakis-Korakakis et al., 2024</span>

            </a>

            <a class="evidence-item" href="https://pubmed.ncbi.nlm.nih.gov/38970765/" target="_blank" rel="noopener">

                <h6>Artumas iki nesėkmės (RIR)</h6>

                <p>RIR metodas padeda dozuoti apkrovą ir valdyti nuovargį neprarandant efektyvumo.</p>

                <span>Robinson et al., 2024</span>

            </a>

            <a class="evidence-item" href="https://journals.physiology.org/doi/full/10.1152/japplphysiol.00476.2024" target="_blank" rel="noopener">

                <h6>Treniruočių tūrio didinimas</h6>

                <p>Savaitinis tūris turi tiesioginę įtaką progresui, todėl planą verta didinti etapais.</p>

                <span>Remmert et al., 2024</span>

            </a>

            <a class="evidence-item" href="https://pubmed.ncbi.nlm.nih.gov/36196347/" target="_blank" rel="noopener">

                <h6>Tūris ir dažnis per savaitę</h6>

                <p>Rezultatams svarbi ne viena treniruotė, o savaitinis tūris ir nuoseklus dažnis.</p>

                <span>Baz-Valle et al., 2022</span>

            </a>

            <a class="evidence-item" href="https://pubmed.ncbi.nlm.nih.gov/27102172/" target="_blank" rel="noopener">

                <h6>Dažnis per savaitę</h6>

                <p>Daugumai tikslų efektyvu treniruoti raumenų grupes bent 2 kartus per savaitę.</p>

                <span>Schoenfeld et al., 2016/2022</span>

            </a>

        </div>

    </div>

</section>



@push('scripts')

<script>

// ---- Būsenos inicializacija (numatyta arba įkelta iš /sports/plan?from_plan=slug) ----
const importedPlan = @json($importedPlan ?? null);

let plan = importedPlan && Array.isArray(importedPlan.schedule)
    ? normalizeImportedPlan(importedPlan)
    : { days: 4, schedule: initDays(4), activeDay: 0 };

function normalizeImportedPlan(data) {
    const days = Math.max(2, Math.min(6, parseInt(data.days || data.schedule.length || 4, 10)));
    const schedule = Array.from({ length: days }, (_, i) => {
        const sourceDay = data.schedule[i] || {};
        const sourceExercises = Array.isArray(sourceDay.exercises) ? sourceDay.exercises : [];

        return {
            name: sourceDay.name || ('Diena ' + (i + 1)),
            exercises: sourceExercises.map((e) => ({
                name: e.name || 'Pratimas',
                sets: parseInt(e.sets ?? 3, 10) || 3,
                reps: String(e.reps ?? '8-12'),
                weight: e.weight ?? '',
                rir: parseInt(e.rir ?? 2, 10),
                rest: parseInt(e.rest ?? 90, 10) || 90,
                notes: e.notes ?? '',
            })),
        };
    });

    return {
        days,
        schedule,
        activeDay: Math.min(Math.max(parseInt(data.activeDay ?? 0, 10), 0), days - 1),
    };
}



function initDays(count) {

    return Array.from({length: count}, (_, i) => ({ name: 'Diena ' + (i + 1), exercises: [] }));

}

function save() { updateTotals(); }



// ---- Viršutinės suvestinės ir dienų tab'ų atvaizdavimas ----
function updateTotals() {

    let ex = 0, sets = 0;

    plan.schedule.forEach(d => { ex += d.exercises.length; d.exercises.forEach(e => sets += parseInt(e.sets) || 0); });

    document.getElementById('totalExercises').textContent = ex;

    document.getElementById('totalSets').textContent = sets;

}



function renderDayTabs() {

    const el = document.getElementById('dayTabs');

    el.innerHTML = plan.schedule.map((d, i) => `

        <button type="button" class="day-tab-v2 ${i === (plan.activeDay ?? 0) ? 'active' : ''}" data-day="${i}">

            ${esc(d.name)} <span class="count">${d.exercises.length}</span>

        </button>

    `).join('');

    el.querySelectorAll('.day-tab-v2').forEach(b => b.addEventListener('click', () => {

        plan.activeDay = parseInt(b.dataset.day);

        save(); renderDayTabs(); renderDayContent();

    }));

}



function renderDayContent() {

    const i = plan.activeDay ?? 0;

    const day = plan.schedule[i];

    const el = document.getElementById('dayContent');

    if (!day) { el.innerHTML = ''; return; }



    const header = `

        <div class="d-flex gap-2 mb-3 align-items-center flex-wrap">

            <input type="text" class="form-control form-control-sm" style="max-width:260px;border-radius:8px" value="${esc(day.name)}" id="dayName">

            <button class="btn btn-sm btn-outline-secondary" id="renameDay" style="border-radius:8px">Pervadinti</button>

            ${day.exercises.length > 0 ? '<button class="btn btn-sm btn-outline-danger ms-auto" id="clearDay" style="border-radius:8px"><i class="bi bi-x-lg"></i> Išvalyti dieną</button>' : ''}

        </div>`;



    const body = day.exercises.length === 0

        ? '<div class="empty-state"><i class="bi bi-inbox"></i><div>Pridėk pratimus iš bibliotekos kairėje</div></div>'

        : day.exercises.map((e, idx) => rowHtml(e, idx)).join('');



    el.innerHTML = header + body;



    document.getElementById('renameDay')?.addEventListener('click', () => {

        const v = document.getElementById('dayName').value.trim();

        if (v) { day.name = v; save(); renderDayTabs(); }

    });

    document.getElementById('clearDay')?.addEventListener('click', () => {

        if (confirm('Išvalyti šios dienos pratimus?')) { day.exercises = []; save(); renderDayTabs(); renderDayContent(); }

    });



    el.querySelectorAll('.ex-row').forEach(row => {

        const idx = parseInt(row.dataset.idx);

        row.querySelectorAll('input, select').forEach(inp => {

            inp.addEventListener('input', () => { day.exercises[idx][inp.dataset.field] = inp.value; save(); });

        });

        row.querySelector('.remove-ex').addEventListener('click', () => { day.exercises.splice(idx, 1); save(); renderDayTabs(); renderDayContent(); });

        row.querySelector('.move-up')?.addEventListener('click', () => { if (idx > 0) { [day.exercises[idx-1], day.exercises[idx]] = [day.exercises[idx], day.exercises[idx-1]]; save(); renderDayContent(); } });

        row.querySelector('.move-down')?.addEventListener('click', () => { if (idx < day.exercises.length - 1) { [day.exercises[idx+1], day.exercises[idx]] = [day.exercises[idx], day.exercises[idx+1]]; save(); renderDayContent(); } });

    });

}



// ---- Pratimo eilutės šablonas ir saugus simbolių konvertavimas ----
function rowHtml(e, idx) {

    return `

    <div class="ex-row" data-idx="${idx}">

        <div class="d-flex justify-content-between align-items-center">

            <div class="ex-title">${esc(e.name)}</div>

            <div class="ex-actions">

                <button class="move-up" title="Aukštyn"><i class="bi bi-arrow-up"></i></button>

                <button class="move-down" title="Žemyn"><i class="bi bi-arrow-down"></i></button>

                <button class="danger remove-ex" title="Pašalinti"><i class="bi bi-x-lg"></i></button>

            </div>

        </div>

        <div class="ex-inputs">

            <div class="ex-field"><label>Sets</label><input type="number" min="1" max="20" value="${e.sets ?? 3}" data-field="sets"></div>

            <div class="ex-field"><label>Reps</label><input type="text" value="${esc(e.reps ?? '8-12')}" data-field="reps"></div>

            <div class="ex-field"><label>Svoris (kg)</label><input type="number" step="0.5" min="0" value="${e.weight ?? ''}" data-field="weight" placeholder="—"></div>

            <div class="ex-field"><label>RIR</label><select data-field="rir">${[0,1,2,3,4,5].map(n => `<option value="${n}" ${e.rir == n ? 'selected' : ''}>${n}</option>`).join('')}</select></div>

            <div class="ex-field"><label>Poilsis (s)</label><input type="number" min="0" step="15" value="${e.rest ?? 90}" data-field="rest"></div>

        </div>

        <div class="ex-notes"><input type="text" value="${esc(e.notes ?? '')}" data-field="notes" placeholder="Pastabos: tempo, forma, progresija..."></div>

    </div>`;

}



function esc(s) { return String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[c]); }



// ---- Dienų skaičiaus valdikliai ----

document.querySelectorAll('#dayCountPicker button').forEach(b => {

    b.addEventListener('click', () => {

        const n = parseInt(b.dataset.count);

        if (plan.days !== n) {

            const old = plan.schedule;

            plan.schedule = initDays(n).map((d, i) => old[i] ?? d);

            plan.days = n;

            plan.activeDay = Math.min(plan.activeDay ?? 0, n - 1);

            save(); updateDayButtons(); renderDayTabs(); renderDayContent();

        }

    });

});

function updateDayButtons() {

    document.querySelectorAll('#dayCountPicker button').forEach(b => b.classList.toggle('active', parseInt(b.dataset.count) === plan.days));

}



// ---- Pratimų bibliotekos filtravimas ----

const libSearch = document.getElementById('exerciseSearch');

const libMuscle = document.getElementById('muscleFilter');

function filterLibrary() {

    const q = libSearch.value.toLowerCase().trim();

    const m = libMuscle.value.toLowerCase().trim();

    document.querySelectorAll('.lib-item').forEach(it => {

        const ok = (!q || it.dataset.search.includes(q)) && (!m || it.dataset.muscles.includes(m));

        it.style.display = ok ? '' : 'none';

    });

}

libSearch.addEventListener('input', filterLibrary);

libMuscle.addEventListener('change', filterLibrary);



// ---- Pratimo pridėjimas į aktyvią dieną ----

document.querySelectorAll('.add-exercise-btn').forEach(btn => {

    btn.addEventListener('click', e => {

        e.stopPropagation();

        const item = btn.closest('.lib-item');

        const i = plan.activeDay ?? 0;

        plan.schedule[i].exercises.push({ name: item.dataset.name, sets: 3, reps: '8-12', weight: '', rir: 2, rest: 90, notes: '' });

        save(); renderDayTabs(); renderDayContent();

    });

});



// ---- Eksportavimo ir atstatymo veiksmai ----

document.getElementById('exportBtn').addEventListener('click', () => {

    const blob = new Blob([JSON.stringify(plan, null, 2)], { type: 'application/json' });

    const a = document.createElement('a');

    a.href = URL.createObjectURL(blob); a.download = 'fitshop-plan.json'; a.click();

});

document.getElementById('resetBtn').addEventListener('click', () => {

    if (confirm('Išvalyti visą planą?')) { plan = { days: 4, schedule: initDays(4), activeDay: 0 }; save(); updateDayButtons(); renderDayTabs(); renderDayContent(); }

});



// ---- Spausdinimo vaizdas ----
// Atidaromas atskiras popup su visomis dienomis aiškiame išdėstyme.
// Taip išvengiama netvarkingo „spausdinti visą puslapį“ rezultato.

document.getElementById('printBtn').addEventListener('click', () => {

    const hasAny = plan.schedule.some(d => d.exercises.length > 0);

    if (!hasAny) { alert('Planas tuščias — pirma pridėk pratimų.'); return; }



    const today = new Date().toLocaleDateString('lt-LT');

    const daysHtml = plan.schedule.map((d, i) => {

        if (!d.exercises.length) return '';

        const rows = d.exercises.map((e, idx) => `

            <tr>

                <td class="num">${idx + 1}</td>

                <td class="name">${esc(e.name)}</td>

                <td class="c">${esc(e.sets || '')}</td>

                <td class="c">${esc(e.reps || '')}</td>

                <td class="c">${e.weight ? esc(e.weight) + ' kg' : '—'}</td>

                <td class="c">${esc(e.rir ?? '')}</td>

                <td class="c">${e.rest ? esc(e.rest) + ' s' : '—'}</td>

                <td class="notes">${esc(e.notes || '')}</td>

            </tr>`).join('');

        const totalSets = d.exercises.reduce((s, x) => s + (parseInt(x.sets) || 0), 0);

        return `

        <section class="day">

            <header>

                <h2>${esc(d.name)}</h2>

                <span class="meta">${d.exercises.length} pratimai · ${totalSets} serijų</span>

            </header>

            <table>

                <thead><tr><th>#</th><th>Pratimas</th><th>Sets</th><th>Reps</th><th>Svoris</th><th>RIR</th><th>Poilsis</th><th>Pastabos</th></tr></thead>

                <tbody>${rows}</tbody>

            </table>

        </section>`;

    }).join('');



    const w = window.open('', '_blank', 'width=900,height=700');

    w.document.write(`<!doctype html><html lang="lt"><head><meta charset="utf-8"><title>Mano treniruočių planas</title>

    <style>

        * { box-sizing: border-box; }

        body { font-family: -apple-system, Segoe UI, Roboto, sans-serif; color: #1e293b; padding: 24px; margin: 0; line-height: 1.45; }

        .header { border-bottom: 2px solid #0f172a; padding-bottom: 12px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: flex-end; }

        .header h1 { margin: 0; font-size: 22px; letter-spacing: -.02em; }

        .header .date { color: #64748b; font-size: 12px; }

        .day { margin-bottom: 20px; page-break-inside: avoid; }

        .day header { display: flex; justify-content: space-between; align-items: baseline; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px; margin-bottom: 8px; }

        .day h2 { font-size: 15px; margin: 0; }

        .day .meta { font-size: 11px; color: #64748b; }

        table { width: 100%; border-collapse: collapse; font-size: 12px; }

        th { text-align: left; padding: 6px 8px; background: #f1f5f9; font-weight: 600; text-transform: uppercase; font-size: 10px; letter-spacing: .04em; color: #475569; }

        td { padding: 7px 8px; border-bottom: 1px solid #e2e8f0; vertical-align: top; }

        td.num { width: 28px; color: #94a3b8; }

        td.name { font-weight: 600; }

        td.c { text-align: center; width: 60px; }

        td.notes { color: #64748b; font-style: italic; font-size: 11px; }

        .footer { margin-top: 24px; font-size: 10px; color: #94a3b8; text-align: center; border-top: 1px solid #e2e8f0; padding-top: 8px; }

        @media print { body { padding: 0; } .noprint { display: none; } }

        .noprint { text-align: center; margin-top: 16px; }

        .noprint button { padding: 8px 18px; border: 0; background: #0f172a; color: #fff; border-radius: 6px; font-weight: 600; cursor: pointer; margin: 0 4px; }

    </style></head><body>

    <div class="header">

        <h1>Mano treniruočių planas</h1>

        <div class="date">${today}</div>

    </div>

    ${daysHtml}

    <div class="footer">FitShop · plano kūrėjas</div>

    <div class="noprint">

        <button onclick="window.print()">Spausdinti</button>

        <button onclick="window.close()" style="background:#64748b">Uždaryti</button>

    </div>

    </body></html>`);

    w.document.close();

});







updateDayButtons(); renderDayTabs(); renderDayContent(); updateTotals();

</script>

@endpush

@endsection

