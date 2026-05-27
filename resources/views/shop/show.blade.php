@extends('layouts.app')

@section('title', $product->name)

@section('content')
<section class="container my-4">

    @php
        $simpleDescription = trim((string) ($product->short_description ?: strip_tags((string) $product->description)));
        if ($simpleDescription === '') {
            $simpleDescription = 'Tai papildas, skirtas kasdieniam vartojimui pagal tavo mitybos ir sporto tikslus.';
        }
        $reviewsCount = $reviews->total();
        $avg          = $reviewsCount ? round($reviews->avg('rating'), 1) : (float) $product->rating;
    @endphp

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Pradžia</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Parduotuvė</a></li>
            @if($product->category->parent)
                <li class="breadcrumb-item"><a href="{{ route('shop.index', ['category' => $product->category->parent->slug]) }}">{{ $product->category->parent->name }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop.index', ['category' => $product->category->parent->slug, 'subcategory' => $product->category->slug]) }}">{{ $product->category->name }}</a></li>
            @else
                <li class="breadcrumb-item"><a href="{{ route('shop.index', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a></li>
            @endif
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-5">

        <div class="col-lg-6">
            <div class="rounded-4 shadow-sm d-flex align-items-center justify-content-center overflow-hidden" style="background:#f5f5f5;min-height:350px;">
                @if($product->image)
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" style="width:100%;height:420px;object-fit:cover;">
                @else
                    <i class="bi bi-box-seam" style="font-size:5rem;color:#bbb;"></i>
                @endif
            </div>

            <div class="mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="m-0">Atsiliepimai @if($reviewsCount)<span class="text-muted fs-6">({{ $reviewsCount }})</span>@endif</h3>
                    <div class="rating fs-5">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi {{ $avg >= $i ? 'bi-star-fill' : ($avg >= $i - 0.5 ? 'bi-star-half' : 'bi-star') }}"></i>
                        @endfor
                        <span class="text-muted small">{{ number_format($avg, 1) }} / 5</span>
                    </div>
                </div>

                @if($reviews->isEmpty())
                    <p class="text-muted">Kol kas nėra atsiliepimų.</p>
                @else
                    <div class="review-list">
                        @foreach($reviews as $rev)
                            <div class="review-item">
                                <div class="review-head">
                                    <div class="review-avatar">{{ mb_strtoupper(mb_substr($rev->user->name ?? '?', 0, 1)) }}</div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold">{{ $rev->user->name ?? 'Anonimas' }}</div>
                                        <div class="small text-muted">{{ $rev->created_at->format('Y-m-d') }}</div>
                                    </div>
                                    <div class="rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi {{ $rev->rating >= $i ? 'bi-star-fill' : 'bi-star' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                @if($rev->title)<div class="review-title">{{ $rev->title }}</div>@endif
                                @if($rev->comment)<p class="review-comment">{{ $rev->comment }}</p>@endif
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-3">{{ $reviews->links() }}</div>
                @endif
            </div>
        </div>

        <div class="col-lg-6">
            <div class="small text-muted">{{ $product->brand }}</div>
            <h1 class="fw-bold">{{ $product->name }}</h1>

            <div class="rating mb-2 fs-5">
                @php $r = (float)$product->rating; @endphp
                @for($i = 1; $i <= 5; $i++)
                    @if($r >= $i) <i class="bi bi-star-fill"></i>
                    @elseif($r >= $i - 0.5) <i class="bi bi-star-half"></i>
                    @else <i class="bi bi-star"></i>
                    @endif
                @endfor
                <span class="text-muted">{{ number_format($r, 1) }} / 5 ({{ $reviewsCount }} atsiliepimų)</span>
            </div>

            <div class="mb-3 fs-3">
                @if($product->sale_price)
                    <span class="price-old">€{{ number_format($product->price, 2) }}</span>
                    <span class="price-new">€{{ number_format($product->sale_price, 2) }}</span>
                @else
                    <strong>€{{ number_format($product->price, 2) }}</strong>
                @endif
            </div>

            <p class="mb-3 text-muted">{{ Str::limit($simpleDescription, 350) }}</p>

            <div class="mb-3">
                @if($product->stock > 0)
                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Sandėlyje ({{ $product->stock }} vnt.)</span>
                @else
                    <span class="badge bg-secondary">Išparduota</span>
                @endif
            </div>

            <form method="POST" action="{{ route('cart.add') }}" class="mb-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="row g-2 align-items-end">
                    <div class="col-auto">
                        <label class="form-label small">Kiekis</label>
                        <input type="number" name="qty" value="1" min="1" max="{{ max(1, $product->stock) }}" class="form-control" style="width:100px">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary btn-lg" @disabled($product->stock <= 0)>
                            <i class="bi bi-cart-plus"></i> Į krepšelį
                        </button>
                    </div>
                </div>
            </form>

            <div class="nutrition-label mb-3">
                <div class="nutrition-label-head">
                    <div>Maistinė vertė</div>
                    @if($product->serving_size)<span>Porcija: {{ $product->serving_size }}</span>@endif
                    @if($product->servings_per_container)<span>Porcijų: {{ $product->servings_per_container }}</span>@endif
                </div>
                <div class="nutrition-label-body">
                    <div><strong>Kalorijos</strong><span>{{ $product->calories ?? 0 }} kcal</span></div>
                    <div><strong>Baltymai</strong><span>{{ $product->protein ?? 0 }} g</span></div>
                    <div><strong>Angliavandeniai</strong><span>{{ $product->carbs ?? 0 }} g</span></div>
                    <div><strong>Iš jų cukrai</strong><span>{{ $product->sugar ?? 0 }} g</span></div>
                    <div><strong>Riebalai</strong><span>{{ $product->fat ?? 0 }} g</span></div>
                    <div><strong>Iš jų sotieji</strong><span>{{ $product->saturated_fat ?? 0 }} g</span></div>
                    <div><strong>Skaidulos</strong><span>{{ $product->fiber ?? 0 }} g</span></div>
                    <div><strong>Natris</strong><span>{{ $product->sodium ?? 0 }} mg</span></div>
                </div>
            </div>
        </div>

    </div>

    @if($related->isNotEmpty())
        <div class="mt-5">
            <h3 class="mb-3">Panašūs produktai</h3>
            <div class="row g-4">
                @foreach($related as $p)
                    <div class="col-md-4">
                        @include('partials.product-card', ['product' => $p])
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</section>
@endsection
