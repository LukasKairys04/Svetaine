<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::latest('published_at')->paginate(20);
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.form', ['item' => new News(['is_published' => true, 'published_at' => now()])]);
    }

    public function store(Request $request)
    {
        News::create($this->validated($request) + ['author_id' => Auth::id()]);
        return redirect()->route('admin.news.index')->with('success', 'Naujiena sukurta.');
    }

    public function edit(News $news)
    {
        return view('admin.news.form', ['item' => $news]);
    }

    public function update(Request $request, News $news)
    {
        $news->update($this->validated($request, $news));
        return redirect()->route('admin.news.index')->with('success', 'Naujiena atnaujinta.');
    }

    public function destroy(News $news)
    {
        $news->delete();
        return back()->with('success', 'Naujiena pašalinta.');
    }

    protected function validated(Request $request, ?News $news = null): array
    {
        $id = $news?->id;
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => "nullable|string|max:255|unique:news,slug,{$id}",
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'image' => 'nullable|string|max:500',
            'published_at' => 'nullable|date',
            'is_published' => 'nullable|boolean',
        ]);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['is_published'] = $request->boolean('is_published');
        return $data;
    }
}
