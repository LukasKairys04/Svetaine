<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Krepšelio puslapis su prekių sąrašu, santrauka ir pasiūlymais.
    public function index(CartService $krepselis)
    {
        $items = $krepselis->items();

        // Parenkam 4 pasiūlymus. Jei krepšelyje kažkas yra — rodome produktus iš
        // tų pačių kategorijų, bet be jau esamų krepšelyje. Kitu atveju — tiesiog
        // populiariausius (pagal reitingų kiekį) produktus.
        $cartProductIds  = $items->pluck('product.id');
        $cartCategoryIds = \App\Models\Product::whereIn('id', $cartProductIds)
            ->pluck('category_id')->filter()->unique();

        $suggestions = \App\Models\Product::query()
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->whereNotIn('id', $cartProductIds)
            ->when($cartCategoryIds->isNotEmpty(), fn ($q) => $q->whereIn('category_id', $cartCategoryIds))
            ->orderByDesc('rating_count')
            ->orderByDesc('rating')
            ->limit(4)
            ->get();

        return view('cart.index', [
            'items'       => $items,
            'summary'     => $krepselis->summary(),
            'suggestions' => $suggestions,
        ]);
    }

    // Prekės pridėjimas į krepšelį. Kiekis neprivalomas — numatytasis 1.
    public function add(Request $request, CartService $krepselis)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty'        => 'nullable|integer|min:1|max:99',
        ]);

        $krepselis->add($data['product_id'], $data['qty'] ?? 1);
        return back()->with('success', 'Prekė pridėta į krepšelį.');
    }

    // Kiekio atnaujinimas. Jei qty=0 — prekė pašalinama per CartService::update().
    public function update(Request $request, CartService $krepselis)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty'        => 'required|integer|min:0|max:99',
        ]);

        $krepselis->update($data['product_id'], $data['qty']);
        return back()->with('success', 'Krepšelis atnaujintas.');
    }

    // Prekės pašalinimas iš krepšelio (iš tikrųjų update su qty=0).
    public function remove(Request $request, CartService $krepselis)
    {
        $data = $request->validate(['product_id' => 'required|exists:products,id']);

        $krepselis->remove($data['product_id']);
        return back()->with('success', 'Prekė pašalinta.');
    }

    // Promo kodo pritaikymas arba pašalinimas. Tuščias kodas — pašalina aktyvų.
    public function applyPromo(Request $request, CartService $krepselis)
    {
        $data  = $request->validate(['code' => 'nullable|string|max:50']);
        $promo = $krepselis->setPromo($data['code'] ?? null);

        if ($data['code'] ?? null) {
            $msg = $promo
                ? 'Promo kodas "' . $promo->code . '" pritaikytas.'
                : 'Promo kodas negalioja.';
            return back()->with($promo ? 'success' : 'error', $msg);
        }

        return back()->with('success', 'Promo kodas pašalintas.');
    }
}
