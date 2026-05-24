<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()
            ->with('category')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating');

        // Category filter
        if ($slug = $request->string('category')->toString()) {
            $cat = Category::where('slug', $slug)->first();
            if ($cat) $query->where('category_id', $cat->id);
        }

        // Search
        if ($q = $request->string('q')->toString()) {
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('brand', 'like', "%{$q}%")
                  ->orWhere('short_description', 'like', "%{$q}%");
            });
        }

        // Price range
        if ($min = $request->input('min_price')) $query->where('price', '>=', (float) $min);
        if ($max = $request->input('max_price')) $query->where('price', '<=', (float) $max);

        // Min rating
        if ($rating = $request->input('rating')) $query->where('rating', '>=', (float) $rating);

        // In stock only
        if ($request->boolean('in_stock')) $query->where('stock', '>', 0);

        // On sale only
        if ($request->boolean('on_sale')) $query->whereNotNull('sale_price');

        // Brand — palaikom tiek viengubą (?brand=Foo), tiek masyvinį (?brands[]=Foo&brands[]=Bar) variantą.
        $selectedBrands = array_filter((array) ($request->input('brands') ?: $request->input('brand')));
        if (!empty($selectedBrands)) {
            $query->whereIn('brand', $selectedBrands);
        }

        // Sorting
        $sort = $request->input('sort', 'popular');
        match ($sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'rating' => $query->orderByDesc('rating'),
            'newest' => $query->latest(),
            default => $query->orderByDesc('featured')->orderByDesc('rating_count'),
        };

        $products = $query->paginate(12)->withQueryString();

        // Kategorijos su produktų kiekiu (kad vartotojas matytų, kiek prekių
        // kiekvienoje kategorijoje prieš filtro pritaikymą).
        $categories = Category::active()->type('product')->orderBy('sort_order')
            ->withCount(['products' => fn ($q) => $q->where('is_active', true)])
            ->get();

        // Prekės ženklai su produktų kiekiu — rodom tik aktyvius ir su bent 1 produktu.
        $brandCounts = Product::active()
            ->whereNotNull('brand')
            ->selectRaw('brand, COUNT(*) as cnt')
            ->groupBy('brand')
            ->orderBy('brand')
            ->pluck('cnt', 'brand');

        $priceBounds = [
            'min' => (float) (Product::active()->min('price') ?? 0),
            'max' => (float) (Product::active()->max('price') ?? 100),
        ];

        return view('shop.index', compact(
            'products', 'categories', 'brandCounts', 'priceBounds', 'selectedBrands'
        ));
    }
}
