<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
 |--------------------------------------------------------------------------
 | Order modelis
 |--------------------------------------------------------------------------
 | Saugo kliento užsakymą: atsiskaitymo duomenis, būseną, kainą ir sukūrtą
 | unikalaus numerio generavimo logiką. Su užsakymu susijusios prekiės
 | saugomos atskiroje `order_items` lentelėje (ryšys hasMany).
 */
class Order extends Model
{
    protected $guarded = [];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'shipping' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    // Užsakymo prekių eilutės (product_name, qty, price snapshot užsakymo metu).
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Vartotojas, kuris pateikė užsakymą.
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Promo kodas, kuris buvo pritaikytas užsakymo metu.
    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }

    // Unikalaus užsakymo numerio generavimas: ORD-XXXXXXXX-YYMMDD.
    public static function generateNumber(): string
    {
        return 'ORD-' . strtoupper(bin2hex(random_bytes(4))) . '-' . now()->format('ymd');
    }
}
