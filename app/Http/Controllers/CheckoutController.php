<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\CartService;
use DomainException;
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
            'billing_name' => ['required', 'string', 'max:255', 'min:2', 'regex:/^[\pL\s\'-]+$/u'],
            'billing_email' => 'required|email:rfc,dns|max:255',
            'billing_phone' => 'nullable|string|max:50|regex:/^[+]?[0-9\s\-()]{7,20}$/',
            'billing_address' => ['required', 'string', 'max:255', 'min:5', 'regex:/[\pL0-9]/u'],
            'billing_city' => ['required', 'string', 'max:120', 'min:2', 'regex:/^[\pL\s\'-]+$/u'],
            'billing_zip' => 'required|string|max:20|regex:/^(LT-)?\d{5}$/i',
            'billing_country' => ['required', 'string', 'max:120', 'min:2', 'regex:/^[\pL\s\'-]+$/u'],
            'notes' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:card,bank,paypal,cod',
        ], [
            'billing_name.regex' => 'Varde gali būti tik raidės, tarpai, brūkšneliai ir apostrofai.',
            'billing_name.min' => 'Vardas turi būti bent 2 simboliai.',
            'billing_phone.regex' => 'Telefono numeris netinkamas.',
            'billing_address.min' => 'Adresas turi būti bent 5 simboliai.',
            'billing_address.regex' => 'Adrese turi būti raidžių arba skaičių.',
            'billing_city.regex' => 'Miesto pavadinime gali būti tik raidės, tarpai, brūkšneliai ir apostrofai.',
            'billing_city.min' => 'Miestas turi būti bent 2 simboliai.',
            'billing_zip.regex' => 'Pašto kodas turi būti formato 12345 arba LT-12345.',
            'billing_country.regex' => 'Šalies pavadinime gali būti tik raidės, tarpai, brūkšneliai ir apostrofai.',
            'billing_country.min' => 'Šalis turi būti bent 2 simboliai.',
        ]);

        if (Auth::check()) {
            $data['billing_name'] = Auth::user()->name;
            $data['billing_email'] = Auth::user()->email;
        }

        try {
            // papildomai patikrinama ar krepšelis dar nėra tuščias
            if ($krepselis->count() === 0) {
                return redirect()->route('shop.index')->with('error', 'Krepšelis tuščias.');
            }

            // pasiimamos prekės ir sumų suvestinė
            $items = $krepselis->items();
            $summary = $krepselis->summary();

            // užsakymas ir jo prekės sukuriami vienoje db transakcijoje
            $order = DB::transaction(function () use ($data, $items, $summary, $krepselis) {
                $lockedProducts = Product::whereIn('id', $items->pluck('product_id'))
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                foreach ($items as $item) {
                    $product = $lockedProducts->get($item->product_id);
                    if (!$product || !$product->is_active || $product->stock < $item->qty) {
                        $name = $item->product?->name ?? 'Prekė';
                        $left = max(0, (int) ($product?->stock ?? 0));
                        throw new DomainException($name . ': sandėlyje liko tik ' . $left . ' vnt.');
                    }
                }

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

                // kiekviena krepšelio prekė įrašoma kaip užsakymo eilutė
                foreach ($items as $item) {
                    $product = $lockedProducts->get($item->product_id);

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'qty' => $item->qty,
                        'price' => $product->effective_price,
                        'subtotal' => (float) $product->effective_price * (int) $item->qty,
                    ]);

                    $product->decrement('stock', $item->qty);
                }

                // jei naudotas promo kodas, padidinamas jo panaudojimų skaičius
                if ($summary['promo']) {
                    $summary['promo']->increment('used_count');
                }

                $krepselis->clear();

                return $order;
            });

            return redirect()->route('checkout.success', $order)->with('success', 'Užsakymas sėkmingai priimtas!');
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        } catch (\Exception $e) {
            \Log::error('Checkout error: ' . $e->getMessage());
            return back()->with('error', 'Įvyko klaida apdorojant užsakymą. Prašome pabandyti vėliau.');
        }
    }

    public function success(Order $order)
    {
        // neleidžia vartotojui matyti svetimo užsakymo, nebent jis admin
        if (Auth::check() && $order->user_id && $order->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403);
        }

        // įkeliamos užsakymo prekės ir parodomas sėkmės puslapis
        $order->load('items');
        return view('checkout.success', compact('order'));
    }
}