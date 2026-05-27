<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // rodomas užsakymų sąrašas su vartotojo informacija
        $query = Order::with('user');

        // filtras pagal užsakymo statusą
        if ($s = $request->input('status')) {
            $query->where('status', $s);
        }

        // paieška pagal užsakymo numerį arba pirkėjo duomenis
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

    public function show(Order $order)
    {
        // rodomos užsakymo detalės su prekėmis ir vartotoju
        $order->load('items.product', 'user');

        return view('admin.orders.show', ['uzsakymas' => $order]);
    }

    public function update(Request $request, Order $order)
    {
        // patikrinami nauji užsakymo ir mokėjimo statusai
        $data = $request->validate([
            'status'         => 'required|in:pending,paid,processing,shipped,completed,cancelled,refunded',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
        ]);

        // atnaujinama užsakymo būsena
        $order->update($data);

        return back()->with('success', 'Užsakymo būsena atnaujinta.');
    }
}