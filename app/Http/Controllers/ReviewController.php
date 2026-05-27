<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // paprastas netinkamų žodžių sąrašas atsiliepimų filtrui
    protected array $profanity = ['fuck','shit','ass','bitch','damn','dick','pussy','cunt','bastard','šūdas','pist','šikt','sūdas','debile','idiote','kvailys'];

    public function store(Request $request, OrderItem $orderItem)
    {
        $orderItem->load('order', 'product');

        if (!$orderItem->product || $orderItem->order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($orderItem->order->payment_status !== 'paid' || $orderItem->order->status !== 'completed') {
            return back()->with('error', 'Atsiliepimą galima palikti tik už apmokėtą ir gautą užsakymą.');
        }

        $product = $orderItem->product;

        // patikrinami atsiliepimo duomenys
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:150',
            'comment' => 'required|string|min:5|max:2000',
        ]);

        // komentaras ir pavadinimas paverčiami mažosiomis raidėmis tikrinimui
        $text = mb_strtolower($data['comment'] . ' ' . ($data['title'] ?? ''));

        // tikrinama, ar atsiliepime nėra netinkamų žodžių
        foreach ($this->profanity as $word) {
            if (str_contains($text, $word)) {
                return back()->withErrors(['comment' => 'Atsiliepime rastas netinkamas žodis.'])->withInput();
            }
        }

        // sukuriamas arba atnaujinamas vartotojo atsiliepimas šiam produktui
        Review::updateOrCreate(
            ['product_id' => $product->id, 'user_id' => Auth::id()],
            array_merge($data, ['approved' => true])
        );

        // perskaičiuojamas produkto reitingo vidurkis ir atsiliepimų kiekis
        $avg = $product->reviews()->avg('rating');
        $count = $product->reviews()->count();
        $product->update([
            'rating' => round($avg, 1),
            'rating_count' => $count,
        ]);

        return back()->with('success', 'Ačiū už atsiliepimą!');
    }
}