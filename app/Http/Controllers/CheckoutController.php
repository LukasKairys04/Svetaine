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

        $order = DB::transaction(function () use ($data, $items, $summary) {
            $order = Order::create(array_merge($data, [
                'order_number' => Order::generateNumber(),
                'user_id' => Auth::id(),
                'status' => 'pending',
                'payment_status' => 'pending',
                'subtotal' => $summary['subtotal'],
                'discount' => $summary['discount'],
                'shipping' => $summary['shipping'],
                'tax' => 0,
                'total' => $summary['total'],
                'promo_code_id' => $summary['promo']?->id,
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

            return $order;
        });

        if ($data['payment_method'] === 'card' && config('services.stripe.secret')) {
            return $this->redirectToStripe($order, $items, $krepselis, $summary);
        }

        if ($summary['promo']) {
            $summary['promo']->increment('used_count');
        }

        $order->update([
            'status' => 'paid',
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        $krepselis->clear();

        return redirect()->route('checkout.success', $order)->with('success', 'Užsakymas sėkmingai priimtas!');
    }

    protected function redirectToStripe(Order $order, $items, CartService $krepselis, array $summary)
    {
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

        $lineItems = [];
        foreach ($items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item->product->name,
                    ],
                    'unit_amount' => (int) round($item->product->effective_price * 100),
                ],
                'quantity' => $item->qty,
            ];
        }

        if ($summary['discount'] > 0) {
            $coupon = $stripe->coupons->create([
                'amount_off' => (int) round($summary['discount'] * 100),
                'currency' => 'eur',
                'duration' => 'once',
                'name' => 'Nuolaida',
            ]);
        }

        if ($summary['shipping'] > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => ['name' => 'Pristatymas'],
                    'unit_amount' => (int) round($summary['shipping'] * 100),
                ],
                'quantity' => 1,
            ];
        }

        $sessionParams = [
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => $lineItems,
            'customer_email' => $order->billing_email,
            'metadata' => ['order_id' => $order->id],
            'success_url' => route('checkout.success', $order) . '?stripe=ok',
            'cancel_url' => route('checkout.cancel', $order),
        ];

        if (isset($coupon)) {
            $sessionParams['discounts'] = [['coupon' => $coupon->id]];
        }

        $session = $stripe->checkout->sessions->create($sessionParams);

        $order->update(['stripe_session_id' => $session->id]);

        $krepselis->clear();

        return redirect($session->url);
    }

    public function success(Order $order)
    {
        if (Auth::check() && $order->user_id && $order->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403);
        }

        if (request('stripe') === 'ok' && $order->payment_status === 'pending' && $order->stripe_session_id) {
            try {
                $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
                $session = $stripe->checkout->sessions->retrieve($order->stripe_session_id);
                if ($session->payment_status === 'paid') {
                    $order->update([
                        'status' => 'paid',
                        'payment_status' => 'paid',
                        'paid_at' => now(),
                    ]);
                }
            } catch (\Exception $e) {
            }
        }

        $order->load('items');
        return view('checkout.success', compact('order'));
    }

    public function cancel(Order $order)
    {
        if (Auth::check() && $order->user_id && $order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->payment_status === 'pending') {
            $order->update(['status' => 'cancelled', 'payment_status' => 'cancelled']);
        }

        return redirect()->route('cart.index')->with('error', 'Mokėjimas atšauktas. Galite bandyti dar kartą.');
    }
}
