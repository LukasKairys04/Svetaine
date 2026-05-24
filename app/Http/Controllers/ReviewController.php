<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ReviewController — leidžia prisijungusiems naudotojams palikti atsiliepimą
 * konkrečiam produktui. Kiekvienas naudotojas gali palikti tik vieną atsiliepimą
 * per produktą (jei bandys antrą kartą — bus atnaujintas esamas).
 */
class ReviewController extends Controller
{
    public function store(Request $request, string $slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $data = $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'title' => 'nullable|string|max:150',
            'comment' => 'required|string|min:5|max:2000',
        ]);

        // updateOrCreate — vienas naudotojas = vienas atsiliepimas produktui.
        Review::updateOrCreate(
            ['product_id' => $product->id, 'user_id' => Auth::id()],
            array_merge($data, ['approved' => true])
        );

        // Perskaičiuojam produkto vidutinį reitingą ir atsiliepimų skaičių.
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
