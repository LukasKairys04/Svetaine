<?php

// Admin: produktų atsiliepimų valdymas (peržiūra, redagavimas, šalinimas)

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product'])->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%$search%"))
                  ->orWhereHas('product', fn($p) => $p->where('name', 'like', "%$search%"))
                  ->orWhere('comment', 'like', "%$search%");
            });
        }

        if ($rating = $request->input('rating')) {
            $query->where('rating', $rating);
        }

        if ($request->input('approved') !== null && $request->input('approved') !== '') {
            $query->where('approved', $request->boolean('approved'));
        }

        $reviews = $query->paginate(25)->withQueryString();
        return view('admin.reviews.index', compact('reviews'));
    }

    public function edit(Review $review)
    {
        $review->load(['user', 'product']);
        return view('admin.reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        $data = $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'title'    => 'nullable|string|max:255',
            'comment'  => 'nullable|string|max:3000',
            'approved' => 'boolean',
        ]);
        $review->update($data);
        return redirect()->route('admin.reviews.index')->with('success', 'Atsiliepimas atnaujintas.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Atsiliepimas pašalintas.');
    }
}
