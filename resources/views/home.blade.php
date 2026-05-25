@extends('layouts.app')

@section('title', 'Pradžia')



@section('content')
<section class="medical-hero">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-lg-6">

                <h1>Jūsų sveikata.<br>Jūsų jėga.<br><span style="color: var(--fs-primary);">Mūsų misija.</span></h1>

                <p class="lead">Aukštos kokybės vitaminai, maisto papildai ir sporto mityba vienoje vietoje.</p>
<form class="deep-search mb-4" id="homeSearchForm" method="GET" action="{{ route('shop.index') }}">

                    <input type="search" id="homeSearchInput" name="q" class="search-input" placeholder="Ieškoti produktų, kategorijų arba ingredientų...">

                    <input type="hidden" id="homeCategoryInput" name="category" value="">

                    <button class="search-btn" type="submit"><i class="bi bi-search"></i></button>

                    <div class="search-suggest" id="homeSearchSuggest" hidden></div>

                </form>

            </div>

            <div class="col-lg-6 d-none d-lg-block">

                <div class="position-relative">

                    <img src="https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=600&h=500&fit=crop" alt="Medical Products" class="img-fluid rounded-4 shadow-lg">

                </div>

            </div>

        </div>

    </div>

</section>
@if($categories->isNotEmpty())

<section class="container my-5 py-4">

    <div class="row g-4 justify-content-center">

        @foreach($categories->take(3) as $cat)

            <div class="col-md-4">

                <a href="{{ route('shop.index', ['category' => $cat->slug]) }}" class="category-photo-card">

                    <img src="{{ $cat->image ?: 'https://images.unsplash.com/photo-1587854692152-cbe660dbde88?w=900&h=600&fit=crop' }}" alt="{{ $cat->name }}">

                    <div class="overlay">

                        <h4>{{ $cat->name }}</h4>

                    </div>

                </a>

            </div>

        @endforeach

    </div>

</section>

@endif
@if($featured->isNotEmpty())

<section class="container my-5 py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="section-title mb-0">Rekomenduojami produktai</h2>

        </div>

        <a href="{{ route('shop.index') }}" class="btn btn-outline-primary">

            <i class="bi bi-grid me-2"></i>Visi produktai

        </a>

    </div>



    <div class="row g-4">

        @foreach($featured->take(3) as $product)

            <div class="col-12 col-md-6 col-lg-4">

                @include('partials.product-card', ['product' => $product])

            </div>

        @endforeach

    </div>

</section>

@endif
<section class="py-5">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, var(--fs-primary-soft) 0%, white 100%);">

                    <div class="card-body p-5 text-center">

                        <i class="bi bi-envelope-heart text-primary fs-1 mb-3"></i>

                        <h3>Prenumeruokite mūsų naujienlaiškį</h3>

                        <p class="text-muted">Gaukite naujausius pasiūlymus ir sveikatos patarimus tiesiai į savo el. paštą</p>

                        <form class="row g-2 justify-content-center">

                            <div class="col-md-8">

                                <input type="email" class="form-control form-control-lg" placeholder="Įveskite savo el. paštą">

                            </div>

                            <div class="col-md-4">

                                <button type="submit" class="btn btn-primary btn-lg w-100">

                                    <i class="bi bi-send me-2"></i>Prenumeruoti

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



@push('scripts')

<script>

(function () {

    const pool = [

        @foreach($categories->take(12) as $c)

            { label: @json($c->name), category: @json($c->slug), url: @json(route('shop.index', ['category' => $c->slug])) },

        @endforeach

        @foreach($featured->take(12) as $p)

            { label: @json($p->name), category: @json(optional($p->category)->slug), url: @json(route('product.show', $p->slug)) },

        @endforeach

    ];



    const input = document.getElementById('homeSearchInput');

    const cat = document.getElementById('homeCategoryInput');

    const suggest = document.getElementById('homeSearchSuggest');

    if (!input || !suggest) return;



    function closeSuggest() {

        suggest.innerHTML = '';

        suggest.hidden = true;

    }



    function pick(item) {

        if (item?.url) {

            window.location.href = item.url;

            return;

        }

        input.value = item.label;

        cat.value = item.category || '';

        closeSuggest();

    }



    input.addEventListener('input', () => {

        const q = input.value.trim().toLowerCase();

        if (q.length < 2) return closeSuggest();



        const items = pool

            .filter(x => x.label && x.label.toLowerCase().includes(q))

            .filter((x, i, arr) => arr.findIndex(y => y.label === x.label) === i)

            .slice(0, 3);



        if (!items.length) return closeSuggest();



        suggest.innerHTML = items.map((x, i) =>

            `<button type="button" class="item" data-i="${i}">${x.label}</button>`

        ).join('');

        suggest.hidden = false;



        suggest.querySelectorAll('.item').forEach(btn => {

            btn.addEventListener('click', () => pick(items[parseInt(btn.dataset.i, 10)]));

        });

    });



    input.addEventListener('blur', () => setTimeout(closeSuggest, 120));

    input.addEventListener('focus', () => {

        if (input.value.trim().length >= 2) input.dispatchEvent(new Event('input'));

    });

})();

</script>

@endpush

@endsection

