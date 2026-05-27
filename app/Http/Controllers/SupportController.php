<?php

namespace App\Http\Controllers;

use App\Models\SupportMessage;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function index()
    {
        // rodomas pagalbos puslapis su naujausiais patvirtintais atsiliepimais
        return view('support.index', [
            'testimonials' => Testimonial::approved()->latest()->take(6)->get(),
        ]);
    }

    public function submit(Request $request)
    {
        // patikrinami pagalbos formos duomenys
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|min:10|max:3000',
        ]);

        // sukuriama nauja pagalbos žinutė
        $message = SupportMessage::create(array_merge($data, [
            'user_id' => Auth::id(),
            'status' => 'new',
        ]));

        return back()->with('success', 'Ačiū! Jūsų žinutė gauta, greitai atsakysime.');
    }

    public function testimonial(Request $request)
    {
        // patikrinami svetainės atsiliepimo duomenys
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|min:10|max:1000',
        ]);

        // atsiliepimas išsaugomas kaip nepatvirtintas
        Testimonial::create(array_merge($data, [
            'user_id' => Auth::id(),
            'approved' => false,
        ]));

        return back()->with('success', 'Ačiū už atsiliepimą! Jis bus paskelbtas po patvirtinimo.');
    }
}