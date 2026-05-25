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

    public function place(Request $request, CartService $krepselis)
    {
        try {
            $data = $request->validate([
                'billing_name' => 'required|string|max:255|min:2',
                'billing_email' => 'required|email|max:255',
                'billing_phone' => 'nullable|string|max:50|regex:/^[+]?[0-9\s\-()]{7,20}$/',
                'billing_address' => 'required|string|max:255|min:5',
                'billing_city' => 'required|string|max:120|min:2',
                'billing_zip' => 'required|string|max:20|regex:/^[a-zA-Z0-9\s\-]{3,10}$/',
                'billing_country' => 'required|string|max:120|min:2',
                'notes' => 'nullable|string|max:1000',
                'payment_method' => 'required|in:card,bank,paypal,cod',
            ], [
                'billing_name.min' => 'Vardas turi būti bent 2 simboliai.',
                'billing_phone.regex' => 'Telefono numeris netinkamas.',
                'billing_address.min' => 'Adresas turi būti bent 5 simboliai.',
                'billing_city.min' => 'Miestas turi būti bent 2 simboliai.',
                'billing_zip.regex' => 'Pašto kodas netinkamas.',
                'billing_country.min' => 'Šalis turi būti bent 2 simboliai.',
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
        } catch (\Exception $e) {
            \Log::error('Checkout error: ' . $e->getMessage());
            return back()->with('error', 'Įvyko klaida apdorojant užsakymą. Prašome pabandyti vėliau.');
        }
    }

    public function success(Order $order)
    {
        if (Auth::check() && $order->user_id && $order->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403);
        }
        $order->load('items');
        return view('checkout.success', compact('order'));
    }
}
