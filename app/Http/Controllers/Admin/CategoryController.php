<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Support\AdminImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::with('parent')->withCount('products');

        if ($q = $request->input('q')) {
            $query->where(fn($w) => $w
                ->where('name', 'like', "%{$q}%")
                ->orWhere('slug', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%"));
        }
        if ($request->filled('type')) $query->where('type', $request->input('type'));
        if ($request->filled('status')) $query->where('is_active', $request->input('status') === 'active');

        $categories = $query->orderBy('type')->orderByRaw('COALESCE(parent_id, id), parent_id IS NOT NULL, sort_order')->paginate(20)->withQueryString();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.form', ['category' => new Category(['is_active' => true, 'type' => 'product'])]);
    }

    public function store(Request $request)
    {
        Category::create($this->validated($request));
        return redirect()->route('admin.categories.index')->with('success', 'Kategorija sukurta.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.form', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $category->update($this->validated($request, $category));
        return redirect()->route('admin.categories.index')->with('success', 'Kategorija atnaujinta.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'Negalima ištrinti — kategorija turi priskirtų produktų.');
        }
        $category->delete();
        return back()->with('success', 'Kategorija pašalinta.');
    }

    protected function validated(Request $request, ?Category $category = null): array
    {
        $id = $category?->id;
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'slug' => "nullable|string|max:120|unique:categories,slug,{$id}",
            'type' => 'required|in:product,sport,nutrition',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        if ($request->hasFile('image_file')) {
            $data['image'] = AdminImage::store($request->file('image_file'), 'categories', 900, 600);
        }
        unset($data['image_file']);
        return $data;
    }
}
