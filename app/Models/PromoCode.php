<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
 |--------------------------------------------------------------------------
 | PromoCode modelis
 |--------------------------------------------------------------------------
 | Nuolaidų kodai su dviem tipais: 'percent' (procentinė) ir 'fixed' (fiksuota
 | suma). Turi galiojimo laiko, minimalios sumos ir naudojimo limito apribojimus.
 | isValid() tikrina visą logiką; discountFor() grąžina tikslų nuolaidos dydį.
 */
class PromoCode extends Model
{
    protected $guarded = [];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'value' => 'decimal:2',
        'min_order' => 'decimal:2',
    ];

    // Patikrina ar kodas gali būti naudojamas: aktyvus, datos, limitas, min. suma.
    public function isValid(?float $subtotal = null): bool
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && now()->lt($this->starts_at)) return false;
        if ($this->expires_at && now()->gt($this->expires_at)) return false;
        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) return false;
        if ($this->min_order !== null && $subtotal !== null && $subtotal < (float) $this->min_order) return false;
        return true;
    }

    // Apskaičiuoja nuolaidos sumą konkretiems pirkimams. percent tipo max = 100%.
    public function discountFor(float $subtotal): float
    {
        if (!$this->isValid($subtotal)) return 0;
        return $this->type === 'percent'
            ? round($subtotal * ((float) $this->value / 100), 2)
            : min((float) $this->value, $subtotal);
    }
}
