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
        // admin kategorijų sąrašas su tėvine kategorija ir produktų kiekiu
        $query = Category::with('parent')->withCount('products');

        // paieška pagal pavadinimą, slug arba aprašymą
        if ($q = $request->input('q')) {
            $query->where(fn($w) => $w
                ->where('name', 'like', "%{$q}%")
                ->orWhere('slug', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%"));
        }

        // filtrai pagal tipą ir statusą
        if ($request->filled('type')) $query->where('type', $request->input('type'));
        if ($request->filled('status')) $query->where('is_active', $request->input('status') === 'active');

        $categories = $query->orderBy('type')->orderByRaw('COALESCE(parent_id, id), parent_id IS NOT NULL, sort_order')->paginate(20)->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        // naujos kategorijos forma su pradinėmis reikšmėmis
        return view('admin.categories.form', ['category' => new Category(['is_active' => true, 'type' => 'product'])]);
    }

    public function store(Request $request)
    {
        // sukuriama nauja kategorija
        Category::create($this->validated($request));

        return redirect()->route('admin.categories.index')->with('success', 'Kategorija sukurta.');
    }

    public function edit(Category $category)
    {
        // kategorijos redagavimo forma
        return view('admin.categories.form', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        // atnaujinama pasirinkta kategorija
        $category->update($this->validated($request, $category));

        return redirect()->route('admin.categories.index')->with('success', 'Kategorija atnaujinta.');
    }

    public function destroy(Category $category)
    {
        // neleidžiama trinti kategorijos, jei ji turi produktų
        if ($category->products()->exists()) {
            return back()->with('error', 'Negalima ištrinti — kategorija turi priskirtų produktų.');
        }

        $category->delete();

        return back()->with('success', 'Kategorija pašalinta.');
    }

    protected function validated(Request $request, ?Category $category = null): array
    {
        $id = $category?->id;

        // bendras validavimas kategorijos kūrimui ir redagavimui
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

        // jei slug neįvestas, jis sugeneruojamas iš pavadinimo
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        // jei įkeltas paveikslėlis, jis apdorojamas ir išsaugomas
        if ($request->hasFile('image_file')) {
            $data['image'] = AdminImage::store($request->file('image_file'), 'categories', 900, 600);
        }

        unset($data['image_file']);

        return $data;
    }
}
