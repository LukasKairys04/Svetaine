<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\SupportMessage;
use App\Models\Testimonial;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'stats' => [
                'products' => Product::count(),
                'users' => User::count(),
                'orders' => Order::count(),
                'revenue' => Order::where('payment_status', 'paid')->sum('total'),
                'pending_support' => SupportMessage::where('status', 'new')->count(),
                'pending_testimonials' => Testimonial::where('approved', false)->count(),
            ],
            'orderStatus' => [
                'pending' => Order::where('status', 'pending')->count(),
                'processing' => Order::where('status', 'processing')->count(),
                'completed' => Order::where('status', 'completed')->count(),
                'cancelled' => Order::where('status', 'cancelled')->count(),
            ],
            'recentOrders' => Order::latest()->take(10)->get(),
            'recentTestimonials' => Testimonial::latest()->take(5)->get(),
        ]);
    }
}
