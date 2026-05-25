<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::active()
            ->where('slug', $slug)
            ->with(['category.parent'])
            ->firstOrFail();

        $reviews = $product->reviews()
            ->where('approved', true)
            ->with('user')
            ->latest()
            ->paginate(5, ['*'], 'reviews_page');

        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('shop.show', compact('product', 'related', 'reviews'));
    }
}
