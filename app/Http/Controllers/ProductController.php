<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        // randamas aktyvus produktas pagal slug(raktažodi) 
        $product = Product::active()
            ->where('slug', $slug)
            ->with(['category.parent'])
            ->firstOrFail();

        // paimami patvirtinti produkto atsiliepimai
        $reviews = $product->reviews()
            ->where('approved', true)
            ->with('user')
            ->latest()
            ->paginate(5, ['*'], 'reviews_page');

        // paimami 3 panašūs produktai iš tos pačios kategorijos
        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('shop.show', compact('product', 'related', 'reviews'));
    }
}