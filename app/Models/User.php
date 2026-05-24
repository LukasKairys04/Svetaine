<?php

namespace App\Models;

/*
 |--------------------------------------------------------------------------
 | User modelis
 |--------------------------------------------------------------------------
 | Pagrindinis autentifikacijos modelis. Saugo profilio duomenis, adresą,
 | kūno parametrus ir administratoriaus žymą (is_admin).
 | Slaptažodis automatiškai hashujamas per cast 'hashed' — nereikia
 | rankiniu būdu kviesti Hash::make() prieš išsaugant per Eloquent.
 */

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name', 'email', 'password', 'is_admin',
    'phone', 'address', 'city', 'country', 'zip',
    'birthdate', 'gender', 'height_cm', 'weight_kg', 'avatar',
    'google_id', 'avatar_url', 'email_verified_at',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',   // Auto-hash: Eloquent saugo jau sušifruotą.
            'is_admin'          => 'boolean',
            'birthdate'         => 'date',
            'height_cm'         => 'decimal:2',
            'weight_kg'         => 'decimal:2',
        ];
    }

    // Visi vartotojo užsakymai.
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Vartotojo parašyti atsiliepimai apie produktus.
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // DB krepšelio eilutės prisijungusiam vartotojui.
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
