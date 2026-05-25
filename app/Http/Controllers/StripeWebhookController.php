<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        if ($secret) {
            try {
                $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $secret);
            } catch (\Exception $e) {
                return response('Invalid signature', 400);
            }
        } else {
            $event = json_decode($payload);
        }

        if (($event->type ?? null) === 'checkout.session.completed') {
            $session = $event->data->object;
            $orderId = $session->metadata->order_id ?? null;

            if ($orderId) {
                $order = Order::find($orderId);
                if ($order && $order->payment_status !== 'paid') {
                    $order->update([
                        'status' => 'paid',
                        'payment_status' => 'paid',
                        'paid_at' => now(),
                    ]);

                    if ($order->promo_code_id) {
                        $order->promoCode?->increment('used_count');
                    }
                }
            }
        }

        return response('OK', 200);
    }
}
