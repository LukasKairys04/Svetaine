<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    /**
     * Home page.
     *
     * Rodo hero, kategorijas ir rekomenduojamas prekes.
     * Naujienos ir testimonials sekcijos pašalintos pagal užsakovo prašymą.
     */
    public function index()
    {
        // Statinis klientų atsiliepimų rinkinys — rodom Flawless tipo kortelėse.
        // Vėliau galima perkelti į DB; tačiau homepage'ui pakanka konstantų.
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
            // Visos produktų kategorijos, išskyrus „Mityba" ir „Sportas" — jos turi atskirus puslapius.
            'categories' => Category::active()->type('product')
                ->whereNotIn('slug', ['mityba', 'sportas'])
                ->orderBy('sort_order')
                ->take(3)
                ->get(),
            // „New arrivals" stiliaus populiariosios prekės — išrinktos kaip featured.
            'featured' => Product::active()->with('category')->featured()->latest()->take(8)->get(),
            // „Most loved" — pagal reitingą ir balsų skaičių.
            'topRated' => Product::active()->with('category')
                ->where('rating_count', '>', 0)
                ->orderByDesc('rating')
                ->orderByDesc('rating_count')
                ->take(4)
                ->get(),
            'testimonials' => $testimonials,
        ]);
    }
}
