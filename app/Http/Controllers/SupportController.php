<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function index()
    {
        return view('support.index', [
            'testimonials' => Testimonial::approved()->latest()->take(6)->get(),
        ]);
    }

    public function testimonial(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|min:10|max:1000',
        ]);

        Testimonial::create(array_merge($data, [
            'user_id' => Auth::id(),
            'approved' => false,
        ]));

        return back()->with('success', 'Ačiū už atsiliepimą! Jis bus paskelbtas po patvirtinimo.');
    }
}