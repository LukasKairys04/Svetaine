@props(['product'])
@php
    $reviewsCount = (int) ($product->reviews_count ?? $product->reviews()->count());
    $reviewsAvg = (float) ($product->reviews_avg_rating ?? ($reviewsCount ? $product->reviews()->avg('rating') : 0));
@endphp

<div class="medical-product-card">
    <a href="{{ route('product.show', $product->slug) }}" class="card-image" style="background:#f5f5f5;display:flex;align-items:center;justify-content:center;min-height:200px;">
        <i class="bi bi-box-seam" style="font-size:3rem;color:#bbb;"></i>
    </a>

    <div class="card-body">
        @if($product->category)
            <div class="card-category">
                @if($product->category->parent)
                    {{ $product->category->parent->name }} <i class="bi bi-chevron-right small"></i>
                @endif
                {{ $product->category->name }}
            </div>
        @endif
        <h5 class="card-title">
            <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none text-dark">{{ $product->name }}</a>
        </h5>

        @if($product->short_description)
            <p class="card-text text-muted small mb-2">{{ Str::limit($product->short_description, 80) }}</p>
        @endif

        <div class="product-reviews-mini mb-2">
            <span class="stars text-warning">
                @for($i = 1; $i <= 5; $i++)
                    <i class="bi {{ $reviewsAvg >= $i ? 'bi-star-fill' : ($reviewsAvg >= $i - 0.5 ? 'bi-star-half' : 'bi-star') }}"></i>
                @endfor
            </span>
            <span class="count">{{ number_format($reviewsAvg, 1) }} ({{ $reviewsCount }})</span>
        </div>

        <div class="card-price">
            @if($product->sale_price)
                <span class="text-muted text-decoration-line-through me-2">€{{ number_format($product->price, 2) }}</span>
                <span class="text-primary fw-bold">€{{ number_format($product->sale_price, 2) }}</span>
            @else
                <span class="text-primary fw-bold">€{{ number_format($product->price, 2) }}</span>
            @endif
        </div>
        <div class="card-footer">
            <a href="{{ route('product.show', $product->slug) }}" class="btn btn-outline-primary btn-sm flex-fill">
                <i class="bi bi-eye me-1"></i>Peržiūrėti
            </a>
            <form method="POST" action="{{ route('cart.add') }}" class="m-0 flex-fill" @disabled($product->stock <= 0)>
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" class="btn btn-primary btn-sm w-100" @disabled($product->stock <= 0)>
                    <i class="bi bi-cart-plus me-1"></i>Į krepšelį
                </button>
            </form>
        </div>
    </div>
</div>
