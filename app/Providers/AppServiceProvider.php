<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Share product categories with all views (for navbar dropdown).
        // "Mityba" ir "Sportas" kategorijos paslepiamos iš Prekės dropdown'o,
        // nes navbar'e jos turi atskirus meniu punktus, vedančius į turinio puslapius.
        View::composer('partials.navbar', function ($view) {
            $view->with('navProductCategories',
                Category::where('type', 'product')
                    ->where('is_active', true)
                    ->whereNotIn('slug', ['mityba', 'sportas'])
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get()
            );
        });
    }
}
