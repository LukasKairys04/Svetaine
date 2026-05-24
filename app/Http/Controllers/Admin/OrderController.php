<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Užsakymų sąrašas su filtravimo galimybe pagal būseną ir paiešką.
    public function index(Request $request)
    {
        $query = Order::with('user');

        if ($s = $request->input('status')) {
            $query->where('status', $s);
        }

        if ($q = $request->input('q')) {
            $query->where(fn($w) => $w
                ->where('order_number', 'like', "%{$q}%")
                ->orWhere('billing_name', 'like', "%{$q}%")
                ->orWhere('billing_email', 'like', "%{$q}%")
            );
        }

        $orders = $query->latest()->paginate(20)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    // Individualus užsakymo peržiūros puslapis su prekėmis ir atsiskaitymo info.
    public function show(Order $order)
    {
        $order->load('items.product', 'user');
        return view('admin.orders.show', ['uzsakymas' => $order]);
    }

    // Užsakymo būsenos ir mokėjimo statuso atnaujinimas iš admin formos.
    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status'         => 'required|in:pending,paid,processing,shipped,completed,cancelled,refunded',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
        ]);

        $order->update($data);
        return back()->with('success', 'Užsakymo būsena atnaujinta.');
    }
}
