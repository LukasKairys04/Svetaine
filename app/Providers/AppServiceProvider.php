<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

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
