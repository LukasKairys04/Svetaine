<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        // rodomas svetainės atsiliepimų sąrašas
        $testimonials = Testimonial::latest()->paginate(20);

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        // patvirtinamas arba atmetamas atsiliepimas
        $testimonial->update(['approved' => $request->boolean('approved')]);

        return back()->with('success', 'Atnaujinta.');
    }

    public function destroy(Testimonial $testimonial)
    {
        // ištrinamas pasirinktas atsiliepimas
        $testimonial->delete();

        return back()->with('success', 'Pašalinta.');
    }
}