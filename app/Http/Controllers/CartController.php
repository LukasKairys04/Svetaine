<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use DomainException;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(CartService $krepselis)
    {
        $items = $krepselis->items();

        // paimami krepšelyje esančių produktų ir kategorijų id
        $cartProductIds  = $items->pluck('product.id');
        $cartCategoryIds = \App\Models\Product::whereIn('id', $cartProductIds)
            ->pluck('category_id')->filter()->unique();

        // parenkami rekomenduojami produktai pagal tas pačias kategorijas
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

    public function add(Request $request, CartService $krepselis)
    {
        // patikrinama ar produktas egzistuoja ir kiekis yra tinkamas
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty'        => 'nullable|integer|min:1|max:99',
        ]);

        try {
            $krepselis->add($data['product_id'], $data['qty'] ?? 1);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Prekė pridėta į krepšelį.');
    }

    public function update(Request $request, CartService $krepselis)
    {
        // patikrinamas produkto id ir naujas kiekis
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty'        => 'required|integer|min:0|max:99',
        ]);

        try {
            $krepselis->update($data['product_id'], $data['qty']);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Krepšelis atnaujintas.');
    }

    public function remove(Request $request, CartService $krepselis)
    {
        // patikrinama, kuri prekė turi būti pašalinta
        $data = $request->validate(['product_id' => 'required|exists:products,id']);

        $krepselis->remove($data['product_id']);
        return back()->with('success', 'Prekė pašalinta.');
    }

    public function applyPromo(Request $request, CartService $krepselis)
    {
        // patikrinamas įvestas promo kodas
        $data  = $request->validate(['code' => 'nullable|string|max:50']);

        // bandoma pritaikyti promo kodą
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