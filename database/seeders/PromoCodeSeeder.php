<?php

namespace Database\Seeders;

use App\Models\PromoCode;
use Illuminate\Database\Seeder;

class PromoCodeSeeder extends Seeder
{
    public function run(): void
    {
        PromoCode::updateOrCreate(
            ['code' => 'WELCOME10'],
            ['type' => 'percent', 'value' => 10, 'min_order' => 20, 'usage_limit' => 1000, 'is_active' => true, 'expires_at' => now()->addYear()]
        );
        PromoCode::updateOrCreate(
            ['code' => 'FIT5'],
            ['type' => 'fixed', 'value' => 5, 'min_order' => 30, 'is_active' => true, 'expires_at' => now()->addMonths(6)]
        );
        PromoCode::updateOrCreate(
            ['code' => 'SUMMER20'],
            ['type' => 'percent', 'value' => 20, 'min_order' => 50, 'is_active' => true, 'expires_at' => now()->addMonths(3)]
        );
    }
}
