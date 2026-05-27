<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\PromoCode;
use DomainException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected const SESSION_KEY = 'cart';
    protected const PROMO_KEY   = 'cart_promo';

    public function items(): Collection
    {
        if (Auth::check()) {
            return \App\Models\CartItem::where('user_id', Auth::id())
                ->with('product')
                ->get()
                ->filter(fn($i) => $i->product)
                ->values();
        }

        $raw = Session::get(self::SESSION_KEY, []);
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

    public function count(): int
    {
        return (int) $this->items()->sum('qty');
    }

    public static function countStatic(): int
    {
        return (new self())->count();
    }

    public function add(int $productId, int $qty = 1): void
    {
        $qty = max(1, $qty);
        $product = Product::findOrFail($productId);
        if (!$product->is_active || $product->stock <= 0) {
            throw new DomainException('Šios prekės šiuo metu nėra sandėlyje.');
        }

        if (Auth::check()) {
            $item = CartItem::firstOrNew(['user_id' => Auth::id(), 'product_id' => $product->id]);
            $newQty = ($item->qty ?? 0) + $qty;
            if ($newQty > $product->stock) {
                throw new DomainException('Sandėlyje liko tik ' . $product->stock . ' vnt. šios prekės.');
            }
            $item->qty = $newQty;
            $item->save();
        } else {
            $cart = Session::get(self::SESSION_KEY, []);
            $newQty = ($cart[$product->id] ?? 0) + $qty;
            if ($newQty > $product->stock) {
                throw new DomainException('Sandėlyje liko tik ' . $product->stock . ' vnt. šios prekės.');
            }
            $cart[$product->id] = $newQty;
            Session::put(self::SESSION_KEY, $cart);
        }
    }

    public function update(int $productId, int $qty): void
    {
        $qty = max(0, $qty);
        $product = Product::findOrFail($productId);
        if ($qty > $product->stock) {
            throw new DomainException('Sandėlyje liko tik ' . $product->stock . ' vnt. šios prekės.');
        }

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

    public function remove(int $productId): void
    {
        $this->update($productId, 0);
    }

    public function clear(): void
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();
        }
        Session::forget(self::SESSION_KEY);
        Session::forget(self::PROMO_KEY);
    }

    public function subtotal(): float
    {
        return (float) $this->items()->sum('subtotal');
    }

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

    public function discount(): float
    {
        $promo = $this->promo();
        return $promo ? $promo->discountFor($this->subtotal()) : 0.0;
    }

    public function shipping(): float
    {
        $sub = $this->subtotal();
        if ($sub === 0.0) return 0.0;
        return $sub >= 50 ? 0.0 : 3.99;
    }

    public function total(): float
    {
        return max(0.0, $this->subtotal() - $this->discount() + $this->shipping());
    }

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
