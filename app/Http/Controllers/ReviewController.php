<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    protected array $profanity = ['fuck','shit','ass','bitch','damn','dick','pussy','cunt','bastard','šūdas','pist','šikt','sūdas','debile','idiote','kvailys'];

    public function store(Request $request, string $slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:150',
            'comment' => 'required|string|min:5|max:2000',
        ]);

        $text = mb_strtolower($data['comment'] . ' ' . ($data['title'] ?? ''));
        foreach ($this->profanity as $word) {
            if (str_contains($text, $word)) {
                return back()->withErrors(['comment' => 'Atsiliepime rastas netinkamas žodis.'])->withInput();
            }
        }

        Review::updateOrCreate(
            ['product_id' => $product->id, 'user_id' => Auth::id()],
            array_merge($data, ['approved' => true])
        );

        $avg = $product->reviews()->avg('rating');
        $count = $product->reviews()->count();
        $product->update([
            'rating' => round($avg, 1),
            'rating_count' => $count,
        ]);

        return redirect()->route('product.show', $product->slug)
            ->with('review_success', 'Ačiū už atsiliepimą!');
    }
}
