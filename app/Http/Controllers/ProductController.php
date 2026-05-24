<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    // Produkto detalus puslapis su patvirtintais atsiliepimais ir susijusiomis prekėmis.
    public function show(string $slug)
    {
        $product = Product::active()
            ->where('slug', $slug)
            ->with([
                'category',
                'reviews' => fn ($q) => $q->where('approved', true)->latest(),
                'reviews.user',
            ])
            ->firstOrFail();

        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('shop.show', compact('product', 'related'));
    }
}
