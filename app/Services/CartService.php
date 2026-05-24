<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\PromoCode;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/*
 |--------------------------------------------------------------------------
 | CartService — dvi saugojimo strategijos
 |--------------------------------------------------------------------------
 | Prisijungęs vartotojas: prekių sąrašas saugomas DB lentelėje `cart_items`.
 | Svečias: sąrašas saugomas sesijoje kaip [product_id => qty] masyvas.
 | Promo kodas visada saugomas sesijoje (abu atvejai).
 */
class CartService
{
    protected const SESSION_KEY = 'cart';    // Sesijos raktas svečio krepšeliui.
    protected const PROMO_KEY   = 'cart_promo'; // Sesijos raktas aktyviam promo kodui.

    // Grąžina visą krepšelio turinį kaip kolekciją objektų {product, qty, subtotal}.
    public function items(): Collection
    {
        if (Auth::check()) {
            return \App\Models\CartItem::where('user_id', Auth::id())
                ->with('product')
                ->get()
                ->filter(fn($i) => $i->product)
                ->values();
        }

        $raw = Session::get(self::SESSION_KEY, []); // [product_id => qty]
        if (empty($raw)) return collect();
        $products = \App\Models\Product::whereIn('id', array_keys($raw))->get()->keyBy('id');
        return collect($raw)->map(function ($qty, $pid) use ($products) {
            $p = $products[$pid] ?? null;
            if (!$p) return null;
            return (object) [
                'product_id' => $p->id,
                'product' => $p,
                'qty' => (int) $qty,
                'subtotal' => (float) $p->effective_price * (int) $qty,
            ];
        })->filter()->values();
    }

    // Bendras prekių kiekis krepšelyje (suma visų qty).
    public function count(): int
    {
        return (int) $this->items()->sum('qty');
    }

    // Statinis variantas — naudojamas Blade view'uose be DI (pvz., navbar badge).
    public static function countStatic(): int
    {
        return (new self())->count();
    }

    // Prekių pridėjimas. Jei prekiė jau yra — kiekis susumuojamas, neperrašomas.
    public function add(int $productId, int $qty = 1): void
    {
        $qty = max(1, $qty);
        $product = Product::findOrFail($productId);
        if (Auth::check()) {
            $item = CartItem::firstOrNew(['user_id' => Auth::id(), 'product_id' => $product->id]);
            $item->qty = ($item->qty ?? 0) + $qty;
            $item->save();
        } else {
            $cart = Session::get(self::SESSION_KEY, []);
            $cart[$product->id] = ($cart[$product->id] ?? 0) + $qty;
            Session::put(self::SESSION_KEY, $cart);
        }
    }

    // Kiekio keitimas. qty=0 veikia kaip pašalinimas.
    public function update(int $productId, int $qty): void
    {
        $qty = max(0, $qty);
        if (Auth::check()) {
            $item = CartItem::where('user_id', Auth::id())->where('product_id', $productId)->first();
            if (!$item) return;
            if ($qty === 0) { $item->delete(); return; }
            $item->qty = $qty;
            $item->save();
        } else {
            $cart = Session::get(self::SESSION_KEY, []);
            if ($qty === 0) {
                unset($cart[$productId]);
            } else {
                $cart[$productId] = $qty;
            }
            Session::put(self::SESSION_KEY, $cart);
        }
    }

    // Prekiės pašalinimas — patogumo metodas, kviečia update(..., 0).
    public function remove(int $productId): void
    {
        $this->update($productId, 0);
    }

    // Viško išvalymas: DB eilutės + sesija + promo kodas.
    public function clear(): void
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();
        }
        Session::forget(self::SESSION_KEY);
        Session::forget(self::PROMO_KEY);
    }

    // Tarpinė suma be nuolaidų ir pristatymo.
    public function subtotal(): float
    {
        return (float) $this->items()->sum('subtotal');
    }

    // Promo kodo nustatymas. Tikrina galiojimą ir min. sumą. Grąžina null jei negalioja.
    public function setPromo(?string $code): ?PromoCode
    {
        if (!$code) {
            Session::forget(self::PROMO_KEY);
            return null;
        }
        $promo = PromoCode::where('code', strtoupper($code))->first();
        if (!$promo || !$promo->isValid($this->subtotal())) {
            Session::forget(self::PROMO_KEY);
            return null;
        }
        Session::put(self::PROMO_KEY, $promo->code);
        return $promo;
    }

    // Grąžina aktyvų promo kodą iš sesijos. Jei negalioja — automatiškai pašalina.
    public function promo(): ?PromoCode
    {
        $code = Session::get(self::PROMO_KEY);
        if (!$code) return null;
        $promo = PromoCode::where('code', $code)->first();
        if (!$promo || !$promo->isValid($this->subtotal())) {
            Session::forget(self::PROMO_KEY);
            return null;
        }
        return $promo;
    }

    // Nuolaidos suma eurais pagal aktyvų promo kodą.
    public function discount(): float
    {
        $promo = $this->promo();
        return $promo ? $promo->discountFor($this->subtotal()) : 0.0;
    }

    // Pristatymo kaina: nemokamas nuo 50 €, kitu atveju 3,99 €. Tuščias krepšelis = 0.
    public function shipping(): float
    {
        $sub = $this->subtotal();
        if ($sub === 0.0) return 0.0;
        return $sub >= 50 ? 0.0 : 3.99;
    }

    // Galutinė suma: subtotal − nuolaida + pristatymas. Negali būti neigiama.
    public function total(): float
    {
        return max(0.0, $this->subtotal() - $this->discount() + $this->shipping());
    }

    // Visi reikalingi duomenys checkout ir krepšelio puslapiams viename masyve.
    public function summary(): array
    {
        return [
            'subtotal' => $this->subtotal(),
            'discount' => $this->discount(),
            'shipping' => $this->shipping(),
            'total' => $this->total(),
            'promo' => $this->promo(),
            'count' => $this->count(),
        ];
    }
}
