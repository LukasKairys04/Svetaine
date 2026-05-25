<?php

namespace App\Models;


use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_admin'          => 'boolean',
            'birthdate'         => 'date',
            'height_cm'         => 'decimal:2',
            'weight_kg'         => 'decimal:2',
        ];
    }

    protected function phone(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? decrypt($value) : $value,
            set: fn ($value) => $value ? encrypt($value) : $value,
        );
    }

    protected function address(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? decrypt($value) : $value,
            set: fn ($value) => $value ? encrypt($value) : $value,
        );
    }

    protected function city(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? decrypt($value) : $value,
            set: fn ($value) => $value ? encrypt($value) : $value,
        );
    }

    protected function country(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? decrypt($value) : $value,
            set: fn ($value) => $value ? encrypt($value) : $value,
        );
    }

    protected function zip(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? decrypt($value) : $value,
            set: fn ($value) => $value ? encrypt($value) : $value,
        );
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
