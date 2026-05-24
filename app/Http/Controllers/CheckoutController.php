<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    // Checkout forma. Jei krepšelis tuščias — peradresuoja į parduotuvę.
    public function index(CartService $krepselis)
    {
        if ($krepselis->count() === 0) {
            return redirect()->route('shop.index')->with('error', 'Jūsų krepšelis tuščias.');
        }
        return view('checkout.index', [
            'items' => $krepselis->items(),
            'summary' => $krepselis->summary(),
            'user' => Auth::user(),
        ]);
    }

    // Užsakymo pateikimas. Sukuria Order + OrderItems DB transakcijoje, išvalo krepšelį.
    public function place(Request $request, CartService $krepselis)
    {
        $data = $request->validate([
            'billing_name' => 'required|string|max:255',
            'billing_email' => 'required|email|max:255',
            'billing_phone' => 'nullable|string|max:50',
            'billing_address' => 'required|string|max:255',
            'billing_city' => 'required|string|max:120',
            'billing_zip' => 'required|string|max:20',
            'billing_country' => 'required|string|max:120',
            'notes' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:card,bank,paypal,cod',
        ]);

        if ($krepselis->count() === 0) {
            return redirect()->route('shop.index')->with('error', 'Krepšelis tuščias.');
        }

        $items = $krepselis->items();
        $summary = $krepselis->summary();

        $order = DB::transaction(function () use ($data, $items, $summary, $krepselis) {
            $order = Order::create(array_merge($data, [
                'order_number' => Order::generateNumber(),
                'user_id' => Auth::id(),
                'status' => 'paid',
                'payment_status' => 'paid',
                'subtotal' => $summary['subtotal'],
                'discount' => $summary['discount'],
                'shipping' => $summary['shipping'],
                'tax' => 0,
                'total' => $summary['total'],
                'promo_code_id' => $summary['promo']?->id,
                'paid_at' => now(),
            ]));

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product->id,
                    'product_name' => $item->product->name,
                    'qty' => $item->qty,
                    'price' => $item->product->effective_price,
                    'subtotal' => $item->subtotal,
                ]);
            }

            if ($summary['promo']) {
                $summary['promo']->increment('used_count');
            }

            $krepselis->clear();

            return $order;
        });

        return redirect()->route('checkout.success', $order)->with('success', 'Užsakymas sėkmingai priimtas!');
    }

    // Sėkmės puslapis po užsakymo. Tik užsakymo savininkas arba admin gali matyti.
    public function success(Order $order)
    {
        if (Auth::check() && $order->user_id && $order->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403);
        }
        $order->load('items');
        return view('checkout.success', compact('order'));
    }
}
