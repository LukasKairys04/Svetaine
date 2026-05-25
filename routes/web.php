<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NutritionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SportController;
use App\Http\Controllers\SupportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/saltiniai', 'references')->name('references');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::post('/product/{slug}/review', [\App\Http\Controllers\ReviewController::class, 'store'])
    ->middleware('auth')->name('product.review');

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::post('/remove', [CartController::class, 'remove'])->name('remove');
    Route::post('/promo', [CartController::class, 'applyPromo'])->name('promo');
});

Route::prefix('checkout')->name('checkout.')->middleware('auth')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/', [CheckoutController::class, 'place'])->name('place');
    Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success');
});

Route::prefix('nutrition')->name('nutrition.')->group(function () {
    Route::get('/', [NutritionController::class, 'index'])->name('index');
    Route::get('/planner', [NutritionController::class, 'planner'])->name('planner');
    Route::get('/{slug}', [NutritionController::class, 'show'])->name('show');
});

Route::prefix('sports')->name('sports.')->group(function () {
    Route::get('/', [SportController::class, 'index'])->name('index');
    Route::get('/builder', [SportController::class, 'builder'])->name('builder');
    Route::get('/plan/{slug}', [SportController::class, 'plan'])->name('plan');
    Route::get('/{slug}', [SportController::class, 'show'])->name('show');
});

Route::prefix('calculators')->name('calculators.')->group(function () {
    Route::get('/', [CalculatorController::class, 'index'])->name('index');
    Route::match(['get', 'post'], '/bmi', [CalculatorController::class, 'bmi'])->name('bmi');
    Route::match(['get', 'post'], '/nutrition', [CalculatorController::class, 'nutrition'])->name('nutrition');
    Route::match(['get', 'post'], '/sport-plan', [CalculatorController::class, 'sportPlan'])->name('sport-plan');
});

Route::get('/support', [SupportController::class, 'index'])->name('support.index');
Route::post('/support', [SupportController::class, 'submit'])->name('support.submit');
Route::post('/support/testimonial', [SupportController::class, 'testimonial'])->name('support.testimonial');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'index'])->name('index');
    Route::put('/', [AccountController::class, 'update'])->name('update');
    Route::put('/password', [AccountController::class, 'password'])->name('password');
    Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
    Route::get('/orders/{uzsakymas}', [AccountController::class, 'order'])->name('order');
    Route::delete('/', [AccountController::class, 'destroy'])->name('destroy');
});

Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')->name('admin.')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class)->except(['show']);
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except(['show']);
        Route::resource('news', \App\Http\Controllers\Admin\NewsController::class)->except(['show']);
        Route::get('orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
        Route::put('orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'update'])->name('orders.update');
        Route::resource('nutrition-plans', \App\Http\Controllers\Admin\NutritionPlanController::class)->except(['show'])->parameters(['nutrition-plans' => 'plan']);
        Route::resource('sport-plans', \App\Http\Controllers\Admin\SportPlanController::class)->except(['show'])->parameters(['sport-plans' => 'plan']);
        Route::get('support', [\App\Http\Controllers\Admin\SupportController::class, 'index'])->name('support.index');
        Route::get('support/{message}', [\App\Http\Controllers\Admin\SupportController::class, 'show'])->name('support.show');
        Route::put('support/{message}', [\App\Http\Controllers\Admin\SupportController::class, 'update'])->name('support.update');
        Route::delete('support/{message}', [\App\Http\Controllers\Admin\SupportController::class, 'destroy'])->name('support.destroy');
        Route::get('testimonials', [\App\Http\Controllers\Admin\TestimonialController::class, 'index'])->name('testimonials.index');
        Route::put('testimonials/{testimonial}', [\App\Http\Controllers\Admin\TestimonialController::class, 'update'])->name('testimonials.update');
        Route::delete('testimonials/{testimonial}', [\App\Http\Controllers\Admin\TestimonialController::class, 'destroy'])->name('testimonials.destroy');
        Route::resource('promo-codes', \App\Http\Controllers\Admin\PromoCodeController::class)->parameters(['promo-codes' => 'promoCode']);
    });
