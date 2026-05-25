@extends('layouts.app')
@section('title', 'Parduotuvė')

@php
    // Aktyvūs filtrų "čipsai" — rodomi viršuje virš rezultatų tinklelio.
    // Kiekvienas čipsas turi label ir `remove` URL, kuriame išimamas tik tas parametras.
    $activeChips = [];
    if (request('q'))        $activeChips[] = ['label' => 'Paieška: "' . request('q') . '"',   'key' => 'q'];
    if (request('category')) {
        $cat = $categories->firstWhere('slug', request('category'));
        $activeChips[] = ['label' => 'Kategorija: ' . ($cat?->name ?? request('category')), 'key' => 'category'];
    }
    foreach ($selectedBrands as $b) {
        $activeChips[] = ['label' => 'Prekės ženklas: ' . $b, 'key' => 'brands', 'value' => $b];
    }
    if (request('min_price')) $activeChips[] = ['label' => 'Nuo €' . request('min_price'), 'key' => 'min_price'];
    if (request('max_price')) $activeChips[] = ['label' => 'Iki €' . request('max_price'), 'key' => 'max_price'];
    if (request('rating'))    $activeChips[] = ['label' => number_format((float) request('rating'), 1) . '+ ⭐', 'key' => 'rating'];
    if (request('in_stock'))  $activeChips[] = ['label' => 'Tik sandėlyje', 'key' => 'in_stock'];
    if (request('on_sale'))   $activeChips[] = ['label' => 'Tik akcija',    'key' => 'on_sale'];

    // Sukuriam URL'ą, kuris išima nurodytą parametrą (jei `value` nurodyta —
    // išimama tik ta reikšmė iš masyvo).
    $removeUrl = function (string $key, ?string $value = null) {
        $params = request()->except('page');
        if ($value !== null && isset($params[$key]) && is_array($params[$key])) {
            $params[$key] = array_values(array_diff($params[$key], [$value]));
            if (empty($params[$key])) unset($params[$key]);
        } else {
            unset($params[$key]);
        }
        return route('shop.index', $params);
    };
@endphp

@section('content')
<section class="container my-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Pradžia</a></li>
            <li class="breadcrumb-item active">Parduotuvė</li>
        </ol>
    </nav>

    <div class="shop-layout">
<aside class="shop-filters">
            <form method="GET" action="{{ route('shop.index') }}" id="shopFilters">
<div class="filter-block">
                    <label class="filter-label">Paieška</label>
                    <div class="search-wrap">
                        <i class="bi bi-search"></i>
                        <input type="search" name="q" value="{{ request('q') }}" placeholder="Ieškoti produktų..." autocomplete="off">
                        <div class="search-suggest" id="shopSearchSuggest" hidden></div>
                    </div>
                </div>
                <details class="filter-block filter-accordion" open>
                    <summary class="filter-title">Kategorija</summary>
                    <select name="category" class="form-select mt-2" onchange="document.querySelector('[name=subcategory]').value=''; this.form.submit();">
                        <option value="">Visos kategorijos</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->slug }}" @selected(request('category') === $c->slug)>
                                {{ $c->name }} ({{ $c->products_count + $c->children->sum('products_count') }})
                            </option>
                        @endforeach
                    </select>
                </details>

                @php
                    $activeCat = $categories->firstWhere('slug', request('category'));
                @endphp
                @if($activeCat && $activeCat->children->isNotEmpty())
                <details class="filter-block filter-accordion" open>
                    <summary class="filter-title">Subkategorija</summary>
                    <div class="filter-list-scroll mt-2">
                        <label class="filter-radio">
                            <input type="radio" name="subcategory" value="" @checked(!request('subcategory'))>
                            <span>Visos</span>
                        </label>
                        @foreach($activeCat->children as $sub)
                            <label class="filter-radio">
                                <input type="radio" name="subcategory" value="{{ $sub->slug }}" @checked(request('subcategory') === $sub->slug)>
                                <span>{{ $sub->name }}</span>
                                <span class="count">{{ $sub->products_count }}</span>
                            </label>
                        @endforeach
                    </div>
                </details>
                @endif
@if($brandCounts->isNotEmpty())
                    <details class="filter-block filter-accordion" @if(!empty($selectedBrands)) open @endif>
                        <summary class="filter-title">Prekės ženklas</summary>
                        <div class="filter-list-scroll mt-2">
                            @foreach($brandCounts as $brand => $count)
                                <label class="filter-check">
                                    <input type="checkbox" name="brands[]" value="{{ $brand }}" @checked(in_array($brand, $selectedBrands, true))>
                                    <span>{{ $brand }}</span>
                                    <span class="count">{{ $count }}</span>
                                </label>
                            @endforeach
                        </div>
                    </details>
                @endif
<details class="filter-block filter-accordion" open>
                    <summary class="filter-title">Kaina</summary>
                    <div class="price-inputs mt-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}"
                               placeholder="Nuo" step="0.01" min="0"
                               aria-label="Minimali kaina eurais">
                        <span>—</span>
                        <input type="number" name="max_price" value="{{ request('max_price') }}"
                               placeholder="Iki" step="0.01" min="0"
                               aria-label="Maksimali kaina eurais">
                    </div>
                    <div class="price-hint">
                        Visos prekės: €{{ number_format($priceBounds['min'], 2) }} – €{{ number_format($priceBounds['max'], 2) }}
                    </div>
                </details>
<details class="filter-block filter-accordion" @if(request('rating')) open @endif>
                    <summary class="filter-title">Minimalus reitingas</summary>
                    <label class="filter-radio">
                        <input type="radio" name="rating" value="" @checked(!request('rating'))>
                        <span>Bet koks</span>
                    </label>
                    @foreach(['4.5', '4.0', '3.5', '3.0'] as $r)
                        <label class="filter-radio">
                            <input type="radio" name="rating" value="{{ $r }}" @checked(request('rating') == $r)>
                            <span class="stars-inline">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= (float) $r ? '-fill' : ($i - 0.5 == (float) $r ? '-half' : '') }}"></i>
                                @endfor
                                <em>ir daugiau</em>
                            </span>
                        </label>
                    @endforeach
                </details>
<details class="filter-block filter-accordion" @if(request('in_stock') || request('on_sale')) open @endif>
                    <summary class="filter-title">Papildomai</summary>
                    <label class="filter-check">
                        <input type="checkbox" name="in_stock" value="1" @checked(request('in_stock'))>
                        <span>Tik sandėlyje</span>
                    </label>
                    <label class="filter-check">
                        <input type="checkbox" name="on_sale" value="1" @checked(request('on_sale'))>
                        <span>Tik akcija</span>
                    </label>
                </details>
<div class="filter-actions">
                    <button type="submit" class="btn btn-primary w-100">Pritaikyti</button>
                    @if(request()->except('page', 'sort'))
                        <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary w-100 mt-2">Atstatyti visus</a>
                    @endif
                </div>
            </form>
        </aside>
<div class="shop-results">
<div class="shop-toolbar">
                <div class="result-count">
                    Rasta <strong>{{ $products->total() }}</strong>
                    {{ $products->total() === 1 ? 'produktas' : ($products->total() % 10 >= 2 && $products->total() % 10 <= 9 && !($products->total() % 100 >= 11 && $products->total() % 100 <= 19) ? 'produktai' : 'produktų') }}
                </div>
                <form method="GET" class="sort-form">
@foreach(request()->except('sort', 'page') as $k => $v)
                        @if(is_array($v))
                            @foreach($v as $vv)
                                <input type="hidden" name="{{ $k }}[]" value="{{ $vv }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endif
                    @endforeach
                    <label>Rūšiuoti:</label>
                    <select name="sort" onchange="this.form.submit()">
                        <option value="popular"    @selected(request('sort', 'popular') === 'popular')>Populiariausios</option>
                        <option value="newest"     @selected(request('sort') === 'newest')>Naujausios</option>
                        <option value="price_asc"  @selected(request('sort') === 'price_asc')>Kaina ↑</option>
                        <option value="price_desc" @selected(request('sort') === 'price_desc')>Kaina ↓</option>
                        <option value="rating"     @selected(request('sort') === 'rating')>Reitingas</option>
                    </select>
                </form>
            </div>
@if(!empty($activeChips))
                <div class="active-chips">
                    @foreach($activeChips as $chip)
                        <a href="{{ $removeUrl($chip['key'], $chip['value'] ?? null) }}" class="chip">
                            {{ $chip['label'] }} <i class="bi bi-x"></i>
                        </a>
                    @endforeach
                    <a href="{{ route('shop.index') }}" class="chip chip-clear">Valyti viską</a>
                </div>
            @endif

            @if($products->isEmpty())
                <div class="empty-state-card">
                    <i class="bi bi-search"></i>
                    <h5>Nieko nerasta</h5>
                    <p class="text-muted">Pabandyk švelnesnius filtrus arba ieškok kitu raktiniu žodžiu.</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-outline-primary">Atstatyti filtrus</a>
                </div>
            @else
                <div class="row g-3">
                    @foreach($products as $p)
                        <div class="col-6 col-md-4">
                            @include('partials.product-card', ['product' => $p])
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 d-flex justify-content-center shop-pagination">
                    {{ $products->onEachSide(1)->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</section>

@push('scripts')
<script>
// Auto-submit kai keičiasi radio/checkbox arba pakeičiama paieška (po 400 ms debounce).
// Kainos input'ai siunčia tik paspaudus Enter arba praradus fokusą, kad vartotojas spėtų įvesti.
(function () {
    const form = document.getElementById('shopFilters');
    if (!form) return;

    const categories = [
        @foreach($categories->take(20) as $c)
            { label: @json($c->name), category: @json($c->slug), url: @json(route('shop.index', ['category' => $c->slug])) },
        @endforeach
    ];
    const brands = [
        @foreach($brandCounts->keys()->take(20) as $b)
            { label: @json($b), category: '', url: @json(route('shop.index', ['q' => $b])) },
        @endforeach
    ];
    const suggestionsPool = [...categories, ...brands];
    const qInput = form.querySelector('input[name="q"]');
    const catSelect = form.querySelector('select[name="category"]');
    const suggest = document.getElementById('shopSearchSuggest');

    function closeSuggest() {
        if (!suggest) return;
        suggest.innerHTML = '';
        suggest.hidden = true;
    }

    function pick(item) {
        if (item?.url) {
            window.location.href = item.url;
            return;
        }
        if (!qInput) return;
        qInput.value = item.label || '';
        if (item.category && catSelect) catSelect.value = item.category;
        closeSuggest();
        form.submit();
    }

    qInput?.addEventListener('input', () => {
        if (suggest) {
            const q = qInput.value.trim().toLowerCase();
            if (q.length >= 2) {
                const items = suggestionsPool
                    .filter(x => x.label && x.label.toLowerCase().includes(q))
                    .filter((x, i, arr) => arr.findIndex(y => y.label === x.label) === i)
                    .slice(0, 3);

                if (items.length) {
                    suggest.innerHTML = items.map((x, i) => `<button type="button" class="item" data-i="${i}">${x.label}</button>`).join('');
                    suggest.hidden = false;
                    suggest.querySelectorAll('.item').forEach(btn => {
                        btn.addEventListener('click', () => pick(items[parseInt(btn.dataset.i, 10)]));
                    });
                } else {
                    closeSuggest();
                }
            } else {
                closeSuggest();
            }
        }
    });

    qInput?.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            closeSuggest();
            form.submit();
        }
    });

    qInput?.addEventListener('blur', () => setTimeout(closeSuggest, 120));
    qInput?.addEventListener('focus', () => {
        if (qInput.value.trim().length >= 2) qInput.dispatchEvent(new Event('input'));
    });

    // Radio/checkbox/select — greitas submit.
    form.querySelectorAll('input[type="radio"], input[type="checkbox"], select').forEach(inp => {
        inp.addEventListener('change', () => form.submit());
    });

    // Kainos laukeliai — submit tik su Enter arba kai vartotojas nustoja redaguoti.
    form.querySelectorAll('input[name="min_price"], input[name="max_price"]').forEach(inp => {
        inp.addEventListener('change', () => form.submit());
    });
})();
</script>
@endpush
@endsection
