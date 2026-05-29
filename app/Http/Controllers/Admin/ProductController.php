<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Support\AdminImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($q = $request->input('q')) {
            $query->where(fn($w) => $w
                ->where('name', 'like', "%{$q}%")
                ->orWhere('brand', 'like', "%{$q}%")
                ->orWhere('slug', 'like', "%{$q}%")
                ->orWhere('short_description', 'like', "%{$q}%"));
        }

        if ($cat = $request->input('category_id')) $query->where('category_id', $cat);
        if ($request->filled('status')) $query->where('is_active', $request->input('status') === 'active');
        if ($request->filled('featured')) $query->where('featured', $request->input('featured') === '1');
        if ($request->filled('stock')) {
            $request->input('stock') === 'in'
                ? $query->where('stock', '>', 0)
                : $query->where('stock', '<=', 0);
        }

        $products = $query->latest()->paginate(20)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        return view('admin.products.form', [
            'product' => new Product(['is_active' => true, 'stock' => 0, 'rating' => 0]),
            'categories' => Category::where('type', 'product')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Produktas sukurtas.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', [
            'product' => $product,
            'categories' => Category::where('type', 'product')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request, $product);
        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Produktas atnaujintas.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with('success', 'Produktas pašalintas.');
    }

    protected function validated(Request $request, ?Product $product = null): array
    {
        $id = $product?->id;

        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => "nullable|string|max:255|unique:products,slug,{$id}",
            'brand' => 'nullable|string|max:120',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
            'rating' => 'nullable|numeric|min:0|max:5',
            'rating_count' => 'nullable|integer|min:0',
            'serving_size' => 'nullable|string|max:50',
            'servings_per_container' => 'nullable|integer|min:0',
            'calories' => 'nullable|numeric|min:0',
            'fat' => 'nullable|numeric|min:0',
            'saturated_fat' => 'nullable|numeric|min:0',
            'carbs' => 'nullable|numeric|min:0',
            'sugar' => 'nullable|numeric|min:0',
            'fiber' => 'nullable|numeric|min:0',
            'protein' => 'nullable|numeric|min:0',
            'sodium' => 'nullable|numeric|min:0',
            'featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        $data['rating'] = $data['rating'] ?? 0;
        $data['rating_count'] = $data['rating_count'] ?? 0;
        $data['stock'] = $data['stock'] ?? 0;

        $data['featured'] = $request->boolean('featured');
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image_file')) {
            $data['image'] = AdminImage::store($request->file('image_file'), 'products', 800, 800);
        }

        unset($data['image_file']);

        return $data;
    }
}