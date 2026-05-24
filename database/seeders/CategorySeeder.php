<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $productCats = [
            ['name' => 'Sporto papildai', 'description' => 'Baltymai, kreatinas, aminorūgštys, BCAA ir kt.'],
            ['name' => 'Mityba', 'description' => 'Sveika mityba, batonėliai, užkandžiai.'],
            ['name' => 'Sportas', 'description' => 'Sporto įranga, aksesuarai, drabužiai.'],
            ['name' => 'Vitaminai', 'description' => 'Vitaminai ir mineralai.'],
            ['name' => 'Gėrimai', 'description' => 'Izotoniniai, energiniai, BCAA gėrimai.'],
        ];

        foreach ($productCats as $i => $c) {
            Category::updateOrCreate(
                ['slug' => Str::slug($c['name'])],
                array_merge($c, ['type' => 'product', 'sort_order' => $i, 'is_active' => true])
            );
        }
    }
}
