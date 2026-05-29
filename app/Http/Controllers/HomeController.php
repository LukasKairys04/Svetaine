<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $testimonials = [
            [
                'name'   => 'Justė M.',
                'text'   => 'Išbandžiau daug baltymų, bet šitas — geriausias. Skonis malonus, tirpsta puikiai, o atsistatymas po treniruotės akivaizdžiai greitesnis.',
                'avatar' => 'https://i.pravatar.cc/120?img=47',
            ],
            [
                'name'   => 'Mantas K.',
                'text'   => 'FitShop tapo mano pagrindine sporto papildų vieta. Prekės atsiranda greitai, pakuotė tvarkinga, o kokybė — nenuvilia.',
                'avatar' => 'https://i.pravatar.cc/120?img=12',
            ],
            [
                'name'   => 'Lina P.',
                'text'   => 'Per mėnesį pastebėjau aiškų skirtumą — daugiau energijos, mažiau nuovargio. Konsultacija dėl mitybos plano buvo tikra pagalba.',
                'avatar' => 'https://i.pravatar.cc/120?img=32',
            ],
        ];

        return view('home', [
            'categories' => Category::active()->type('product')
                ->whereNull('parent_id')
                ->whereNotIn('slug', ['mityba', 'sportas'])
                ->orderBy('sort_order')
                ->take(3)
                ->get(),

            'featured' => Product::active()->with('category.parent')->featured()->latest()->take(8)->get(),
            'topRated' => Product::active()->with('category.parent')
                ->where('rating_count', '>', 0)
                ->orderByDesc('rating')
                ->orderByDesc('rating_count')
                ->take(4)
                ->get(),

            'testimonials' => $testimonials,
        ]);
    }
}