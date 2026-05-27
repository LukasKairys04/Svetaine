<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        // pradinė produktų užklausa katalogui
        $query = Product::active()
            ->with('category.parent')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating');

        // filtras pagal pagrindinę kategoriją 
        if ($slug = $request->string('category')->toString()) {
            $cat = Category::where('slug', $slug)->first();
            if ($cat) {
                $catIds = $cat->children()->pluck('id')->push($cat->id);
                $query->whereIn('category_id', $catIds);
            }
        }

        if ($subSlug = $request->string('subcategory')->toString()) {
            $sub = Category::where('slug', $subSlug)->first();
            if ($sub) $query->where('category_id', $sub->id);
        }

        // paieška pagal pavadinimą, brand arba trumpą aprašymą
        if ($q = $request->string('q')->toString()) {
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('brand', 'like', "%{$q}%")
                  ->orWhere('short_description', 'like', "%{$q}%");
            });
        }

        // kiti filtrai: kaina, reitingas, likutis, akcija ir brands
        if ($min = $request->input('min_price')) $query->where('price', '>=', (float) $min);
        if ($max = $request->input('max_price')) $query->where('price', '<=', (float) $max);

        if ($rating = $request->input('rating')) $query->where('rating', '>=', (float) $rating);

        if ($request->boolean('in_stock')) $query->where('stock', '>', 0);

        if ($request->boolean('on_sale')) $query->whereNotNull('sale_price');

        $selectedBrands = array_filter((array) ($request->input('brands') ?: $request->input('brand')));
        if (!empty($selectedBrands)) {
            $query->whereIn('brand', $selectedBrands);
        }

        // rūšiavimas pagal pasirinktą kriterijų
        $sort = $request->input('sort', 'popular');
        match ($sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'rating' => $query->orderByDesc('rating'),
            'newest' => $query->latest(),
            default => $query->orderByDesc('featured')->orderByDesc('rating_count'),
        };

        $products = $query->paginate(12)->withQueryString();

        // duomenys filtrų meniu šone
        $categories = Category::active()->type('product')
            ->whereNull('parent_id')
            ->with(['children' => fn ($q) => $q->active()->orderBy('sort_order')
                ->withCount(['products' => fn ($q2) => $q2->where('is_active', true)])])
            ->withCount(['products' => fn ($q) => $q->where('is_active', true)])
            ->orderBy('sort_order')
            ->get();

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

    public function search(Request $request)
    {
        $q = $request->input('q');
        if (strlen($q) < 2) {
            return response()->json(['results' => []]);
        }

        // greita paieška produktų pasiūlymams
        $products = Product::active()
            ->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('brand', 'like', "%{$q}%")
                  ->orWhere('short_description', 'like', "%{$q}%");
            })
            ->limit(8)
            ->get(['id', 'name', 'slug', 'price', 'sale_price', 'image']);

        return response()->json([
            'results' => $products->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'price' => $p->effective_price,
                    'image' => $p->image,
                ];
            })
        ]);
    }
}