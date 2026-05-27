<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($stock = $request->input('stock')) {
            match ($stock) {
                'in' => $query->where('stock', '>', 5),
                'low' => $query->whereBetween('stock', [1, 5]),
                'out' => $query->where('stock', '<=', 0),
                default => null,
            };
        }

        $products = $query->orderBy('stock')->orderBy('name')->paginate(20)->withQueryString();
        $categories = Category::where('type', 'product')->orderBy('name')->get();

        return view('admin.inventory.index', compact('products', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'stock' => 'required|integer|min:0|max:999999',
        ], [
            'stock.required' => 'Įveskite prekės likutį.',
            'stock.integer' => 'Likutis turi būti sveikas skaičius.',
            'stock.min' => 'Likutis negali būti neigiamas.',
        ]);

        $product->update(['stock' => $data['stock']]);

        return back()->with('success', 'Sandėlio likutis atnaujintas.');
    }
}
